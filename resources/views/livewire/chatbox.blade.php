<?php

use Livewire\Attributes\{On};
use Livewire\Volt\Component;
use OpenAI\Laravel\Facades\OpenAI;

new class extends Component
{
    public $msg = '';

    public $conversationHistory = [];

    public $conversationHistoryLength;

    public function mount()
    {
        $this->conversationHistoryLength = config('chat.conversation_length');
        // Load latest 10 messages from the database
        $messages = auth()->user()->messages()->orderBy('created_at', 'desc')->take(10)->get();

        // Populate conversationHistory with fetched messages
        foreach ($messages as $message) {
            $this->conversationHistory[] = (object) [
                'role' => $message->sender_type == 'api' ? 'assistant' : 'user',
                'content' => $message->content,
            ];
        }

        // Reverse the conversationHistory to show latest messages first
        $this->conversationHistory = array_reverse($this->conversationHistory);
    }

    public function sendMsg(): void
    {
        if ($this->msg) {
            $this->conversationHistory[] = (object) ['role' => 'user', 'content' => $this->msg];

            auth()
                ->user()
                ->messages()
                ->create([
                    'content' => $this->msg,
                    'sender_type' => 'user',
                ]);
            $this->dispatch('message-sent', message: $this->conversationHistory);
            $this->msg = '';
        }
    }

    #[On('message-sent')]
    public function aiResponse($message)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $message,
        ]);

        $this->conversationHistory[] = (object) ['role' => 'user', 'content' => $this->msg];

        auth()
            ->user()
            ->messages()
            ->create([
                'content' => $result->choices[0]->message->content,
                'sender_type' => 'api',
            ]);

        $conversationLength = $this->conversationHistoryLength;
        if (count($this->conversationHistory) > $conversationLength) {
            $this->conversationHistory = array_slice($this->conversationHistory, -$conversationLength, $conversationLength);
        }
    }

    public function with(): array
    {
        return [
            'messages' => auth()->user()->messages,
        ];
    }
}; ?>

<div>
    <div class="flex flex-col h-[600px] w-full max-w-xl mx-auto rounded-xl overflow-hidden border">
        <div class="bg-primary text-primary-foreground flex items-center justify-between px-4 py-3 border-b">
            <div class="font-medium">Chat with ChatGPT</div>
        </div>
        <div id="conversation" class="flex-1 overflow-auto p-4 space-y-4" x-ref="scrollChat" x-init="height = $refs.scrollChat.scrollHeight;
        $nextTick(() => $refs.scrollChat.scrollTop = height)">
            @foreach ($messages as $message)
                <x-chat.chat-bubble :message="$message" />
            @endforeach
            <div wire:loading disabled type="button"
                class="py-2.5 px-5 ml-4 me-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center">
                <svg aria-hidden="true" role="status"
                    class="inline w-4 h-4 me-3 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="#1C64F2" />
                </svg>
                Loading...
                </button>
            </div>

        </div>
        <form wire:submit='sendMsg' class="bg-primary px-4 py-3 border-t">
            <div class="flex flex-col gap-2">
                <textarea wire:model='msg'
                    class="flex min-h-[80px] border border-input bg-background text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full rounded-lg resize-none p-2"
                    placeholder="Type your message..." rows="1"></textarea>
                <x-primary-button class="w-1/2 justify-center">Send</x-primary-button>
            </div>
        </form>
    </div>
