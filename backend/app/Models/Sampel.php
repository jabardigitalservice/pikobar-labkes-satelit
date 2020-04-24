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

    protected $dates = [
        'tanggal_sampel'
    ];

    protected $casts = [
        'tanggal_sampel'=> 'date:Y-m-d',
        'waktu_sampel'=> 'datetime:H:i',
        'created_at'=> 'datetime:Y-m-d H:i:s',
        'updated_at'=> 'datetime:Y-m-d H:i:s',
    ];

    public function pengambilanSampel()
    {
        return $this->belongsTo(PengambilanSampel::class);
    }
}
