<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasyankes extends Model
{
    protected $table = 'fasyankes';

    protected $connection = 'labkes';

    protected $fillable = [
        'nama',
        'tipe', // Rumah sakit | Dinkes
        'kota_id'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}
