<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RiwayatLawatan extends Pivot
{
    protected $table = 'riwayat_lawatan';

    protected $fillable = [
        'tanggal_lawatan',
        'nama_kota',
        'nama_negara'
    ];

    protected $casts = [
        'tanggal_lawatan'=> 'date:Y-m-d',
    ];
    
}
