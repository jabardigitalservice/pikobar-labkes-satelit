<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusRegister extends Model
{
    protected $table = 'status_register';

    protected $fillable = [
        'register_status',
        'chamber',
        'deskripsi',
    ];
    protected $primaryKey   = 'register_status';
    protected $casts = [
        'register_status' => 'string',
    ];
    protected $keyType = 'string';
}
