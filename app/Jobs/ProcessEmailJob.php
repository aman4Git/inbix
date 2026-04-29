<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\AIConfig;
use App\Factories\AIProviderFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AIReplyMail;

class ProcessEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailId;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(int $emailId)
    {
        $this->emailId = $emailId;
    }

    public function handle()
    {
        $email = Email::find($this->emailId);

        if (!$email) return;

        try {
            $email->update(['status' => 'processing']);

            $config = AIConfig::where('is_active', true)->first();

            $providerName = $config->provider ?? config('ai.provider');

            $provider = AIProviderFactory::make($providerName);

            $result = $provider->generateReply(
                $email->subject,
                $email->body,
                [
                    'tone' => $config->tone ?? config('ai.tone'),
                    'max_tokens' => $config->max_tokens ?? config('ai.max_tokens'),
                    'temperature' => $config->temperature ?? config('ai.temperature'),
                ]
            );

            $email->update([
                'ai_response' => $result['text'],
                'status' => 'responded',
                'provider' => $providerName,
                'tokens_used' => $result['tokens']
            ]);

            // Send email to user
            if ($email->from_email) {
            Mail::to($email->from_email)->send(new AIReplyMail($email));
        }

        } catch (\Throwable $e) {
           Log::error('AI Job Failed', [
                'error' => $e->getMessage(),
                'email_id' => $this->emailId
            ]);

            $email->update(['status' => 'failed']);

            throw $e; // keep retry working
        }
    }
}
