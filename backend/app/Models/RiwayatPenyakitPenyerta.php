<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;


class RiwayatPenyakitPenyerta extends Model
{
    protected $table = 'riwayat_penyakit_penyerta';

    protected $fillable = [
        'daftar_penyakit',
        'keterangan_lain'
    ];

    public $timestamps = false;

    protected $casts = [
        'daftar_penyakit'=> 'array'
    ];

    protected function register()
    {
        return $this->belongsTo(Register::class);
    }
}
