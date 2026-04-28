<?php

namespace App\Providers\AI;

use App\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;

class GeminiProvider implements AIProviderInterface
{
    public function generateReply(string $subject, string $body, array $config): array
    {
        $prompt = "
        Tone: {$config['tone']}
        Keep response within {$config['max_tokens']} tokens.

        Subject: $subject
        Body: $body
        ";

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . config('services.gemini.key'),
            [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]
        );

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'text' => $text,
            'tokens' => strlen($text)
        ];
    }
}
