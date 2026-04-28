<?php

namespace App\Factories;

use App\Providers\AI\GeminiProvider;

class AIProviderFactory
{
    public static function make(string $provider)
    {
        return match ($provider) {
            'gemini' => new GeminiProvider(),
            default  => throw new \Exception('Invalid provider'),
        };
    }
}
