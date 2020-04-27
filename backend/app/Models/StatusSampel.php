<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusSampel extends Model
{
    protected $table = 'status_sampel';

    protected $fillable = [
        'sampel_status',
        'chamber',
        'deskripsi',
    ];
    protected $primaryKey   = 'sampel_status';
    protected $casts = [
        'sampel_status' => 'string',
    ];
    protected $keyType = 'string';
}
