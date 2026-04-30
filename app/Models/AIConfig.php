<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIConfig extends Model
{
    protected $table = 'ai_configs';

    protected $fillable = [
        'provider',
        'max_tokens',
        'temperature',
        'tone',
        'is_active'
    ];

    protected $casts = [
        'max_tokens'  => 'integer',
        'temperature' => 'float',
        'is_active'   => 'boolean',
    ];
}
