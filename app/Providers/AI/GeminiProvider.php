<?php

namespace App\Providers\AI;

use App\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . config('services.gemini.key'),
            [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "temperature"     => (float) $config['temperature'],
                    "maxOutputTokens" => (int) $config['max_tokens'],
                ],
            ]
        );

        Log::info('Gemini Raw Response', [
            'response' => $response->json()
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gemini API failed: ' . $response->body());
        }

        $data = $response->json();

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            throw new \Exception('Empty response from Gemini');
        }

        return [
            'text'   => $text,
            'tokens' => (int) ($data['usageMetadata']['totalTokenCount'] ?? 0),
        ];
    }
}
