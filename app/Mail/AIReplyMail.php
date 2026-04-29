<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AIReplyMail extends Mailable
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->email->subject)
            ->view('emails.reply')
            ->with([
                'content' => $this->email->ai_response
            ]);
    }
}
