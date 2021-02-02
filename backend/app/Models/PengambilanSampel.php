<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengambilanSampel extends Model
{
    protected $table = 'pengambilan_sampel';

    protected $fillable = [
        'sampel_diambil',
        'sampel_diterima',
        'diterima_dari_faskes',
        'penerima_sampel',
        'sampel_id',
        'catatan',
        'status',
        'nomor_ekstraksi',
        'sampel_rdt'
    ];

    // public function sampel()
    // {
    //     return $this->hasMany(Sampel::class);
    // }

    public function register()
    {
        return $this->belongsToMany(Register::class, 'pengambilan_sampel_registrasi', 'pengambilan_sampel_id', 'register_id');
    }
}
