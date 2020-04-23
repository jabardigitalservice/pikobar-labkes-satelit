<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyakitPenyerta extends Model
{
    protected $table = 'penyakit_penyerta';

    protected $fillable = [
        'nama_penyakit',
        'is_active',
    ];

    public $timestamps = false;
}
