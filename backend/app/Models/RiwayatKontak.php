<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RiwayatKontak extends Pivot
{
    protected $table = 'riwayat_kontak';

    protected $fillable = [
        'nama_lengkap',
        'alamat',
        'hubungan',
        'tanggal_awal',
        'tanggal_akhir',
        'positif_covid19',
        'keluarga_sakit_sejenis',
    ];

    protected $casts = [
        'tanggal_awal'=> 'date:Y-m-d',
        'tanggal_akhir'=> 'date:Y-m-d',
    ];
}
