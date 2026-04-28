<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIConfig extends Model
{
    protected $fillable = [
        'provider',
        'max_tokens',
        'temperature',
        'tone',
        'is_active'
    ];
}
