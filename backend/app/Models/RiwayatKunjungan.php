<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKunjungan extends Model
{
    protected $table = 'riwayat_kunjungan';

    protected $fillable = [
        'register_id',
        'riwayat'
    ];

    public function register()
    {
        return $this->belongsTo(Register::class);
    }

    protected $casts = [
        'riwayat'=> 'array'
    ];
    
}
