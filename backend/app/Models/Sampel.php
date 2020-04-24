<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sampel extends Model
{
    protected $table = 'sampel';

    protected $fillable = [
        'jenis_sampel',
        'petugas_pengambil_sampel',
        'tanggal_sampel',
        'waktu_sampel',
        'nomor_barcode',
        'nama_diluar_jenis',
        'status'
    ];

    public function pengambilanSampel()
    {
        return $this->belongsTo(PengambilanSampel::class);
    }
}
