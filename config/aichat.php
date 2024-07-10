<?php

return [
    /*
        |--------------------------------------------------------------------------
        | Conversation Length
        |--------------------------------------------------------------------------
        | Represents a conversation and its length.
        | The conversation length determines how many chat bubbles are remembered as context each time we visit.
        | The default conversation length is set to 10.
        |
        */
    'conversation_length' => env('AI_CONVERSATION_LENGTH', 10),
];
