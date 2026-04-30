<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'from_email',
        'subject',
        'body',
        'ai_response',
        'status',
        'provider',
        'tokens_used'
    ];

    protected $casts = [
        'tokens_used' => 'integer',
    ];
}
