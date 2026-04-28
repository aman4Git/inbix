<?php

return [
    'provider'    => env('AI_PROVIDER', 'gemini'),
    'max_tokens'  => env('AI_MAX_TOKENS', 300),
    'temperature' => env('AI_TEMPERATURE', 0.7),
    'tone'        => env('AI_TONE', 'professional'),
];
