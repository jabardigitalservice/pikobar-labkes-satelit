<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GejalaPasien extends Pivot
{
    protected $table = 'gejala_pasien';

    protected $fillable = [
        'register_id',
        'pasien_id',
        'pasien_rdt',
        'hasil_rdt_positif',
        'tanggal_rdt',
        'tanggal_onset_gejala',
        'daftar_gejala',
        'gejala_lain'
    ];

    protected $dates = [
        'tanggal_rdt',
        'tanggal_onset_gejala',
    ];

    protected $casts = [
        'daftar_gejala'=> 'array',
        'tanggal_rdt'=> 'date:Y-m-d',
        'tanggal_onset_gejala'=> 'date:Y-m-d'
    ];
}
