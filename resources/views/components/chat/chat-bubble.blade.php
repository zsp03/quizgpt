<div class="flex items-center p-4 {{ $message->sender_type == 'user' ? 'justify-end' : '' }}">
    <div @class([
        'px-4 py-2 rounded-lg max-w-[80%]',
        'bg-neutral-300' => $message->sender_type == 'api',
        'bg-gray-700' => $message->sender_type == 'user',
        'text-gray-200' => $message->sender_type == 'user',
    ])>
        {{ $message->content }}
    </div>
</div>
