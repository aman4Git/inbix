<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Mail\Mailable;

class AIReplyMail extends Mailable
{
    public function __construct(public Email $email) {}

    public function build(): self
    {
        return $this->subject('Re: ' . $this->email->subject)
            ->view('emails.reply')
            ->with([
                'content' => $this->email->ai_response,
            ]);
    }
}
