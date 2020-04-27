<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampelLog extends Model
{
    protected $table = 'sampel_log';

    protected $fillable = [
        'sampel_id',
        'sampel_status',
        'sampel_status_before',
        'user_id',
        'metadata',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
