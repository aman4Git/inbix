<?php

namespace App\Services;

use App\Models\Email;
use App\Models\AIConfig;
use App\Factories\AIProviderFactory;
use App\Mail\AIReplyMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function process(array $data): Email
    {
        $email = Email::create([
            'from_email' => $data['from_email'] ?? null,
            'subject'    => $data['subject'],
            'body'       => $data['body'],
            'status'     => 'received'
        ]);

        try {
            $email->update(['status' => 'processing']);

            $config = AIConfig::where('is_active', true)->first();

            $providerName = $config?->provider ?? config('ai.provider');

            $provider = AIProviderFactory::make($providerName);

            $result = $provider->generateReply(
                $email->subject,
                $email->body,
                [
                    'tone'        => $config?->tone ?? config('ai.tone'),
                    'max_tokens'  => $config?->max_tokens ?? config('ai.max_tokens'),
                    'temperature' => $config?->temperature ?? config('ai.temperature'),
                ]
            );

            $email->update([
                'ai_response' => $result['text'],
                'status'      => 'responded',
                'provider'    => $providerName,
                'tokens_used' => $result['tokens']
            ]);

            if ($email->from_email) {
                Mail::to($email->from_email)->send(new AIReplyMail($email));
            }

        } catch (\Throwable $e) {
            Log::error('Email processing failed', [
                'email_id' => $email->id,
                'from_email' => $email->from_email,
                'error' => $e->getMessage(),
            ]);

            $email->update(['status' => 'failed']);

            throw $e;
        }

        return $email;
    }
}
