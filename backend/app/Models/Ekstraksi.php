<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstraksi extends Model
{
    protected $table = 'ekstraksi';

    protected $fillable = [
        'nama',
    ];

    protected $dates = [
        'tanggal_penerimaan_sampel',
        'tanggal_mulai_ekstraksi',
        'tanggal_pengiriman_rna',
    ];

}
