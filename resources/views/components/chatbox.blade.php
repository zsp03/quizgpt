<div class="flex flex-col h-[600px] w-full max-w-xl mx-auto rounded-xl overflow-hidden border">
    <div class="bg-primary text-primary-foreground flex items-center justify-between px-4 py-3 border-b">
        <div class="font-medium">Chat with Jane</div>
        <div class="flex items-center gap-2">
            <button
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <span class="sr-only">Search</span>
            </button>
            <button
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 w-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5">
                    <polyline points="18 8 22 12 18 16"></polyline>
                    <polyline points="6 8 2 12 6 16"></polyline>
                    <line x1="2" x2="22" y1="12" y2="12"></line>
                </svg>
                <span class="sr-only">More</span>
            </button>
        </div>
    </div>
    <div class="flex-1 overflow-auto p-4 space-y-4">
        @foreach (auth()->user()->messages as $message)
            <x-chat.chat-bubble :message="$message" />
        @endforeach
    </div>
    <div class="bg-muted px-4 py-3 border-t">
        <div class="relative">
            <textarea
                class="flex min-h-[80px] border border-input bg-background text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full rounded-lg resize-none p-2"
                placeholder="Type your message..." rows="1"></textarea>
            <button
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 w-10"
                type="submit"></button>
        </div>
    </div>
</div>
