<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'kota';

    public $timestamps = false;

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
