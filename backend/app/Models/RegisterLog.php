<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterLog extends Model
{
    protected $table = 'register_log';

    protected $fillable = [
        'register_id',
        'register_status',
        'register_status_before',
        'user_id',
        'metadata',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
