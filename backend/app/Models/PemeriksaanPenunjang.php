<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PemeriksaanPenunjang extends Pivot
{
    protected $table = 'pemeriksaan_penunjang';

    protected $fillable = [
        'register_id',
        'pasien_id',
        'xray_paru',
        'penjelasan_xray',
        'leukosit',
        'limfosit',
        'trombosit',
        'ventilator',
        'status_kesehatan', // pulang, dirawat, meninggal
        'keterangan_lab' // keterangan lainnya
    ];

    protected $casts = [
        'leukosit'=> 'decimal:2',
        'limfosit'=> 'decimal:2',
        'trombosit'=> 'decimal:2',
    ];
}
