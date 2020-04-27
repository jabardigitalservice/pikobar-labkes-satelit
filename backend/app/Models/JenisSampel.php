<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSampel extends Model
{
    protected $table = 'jenis_sampel';

    protected $fillable = [
        'nama',
    ];
}
