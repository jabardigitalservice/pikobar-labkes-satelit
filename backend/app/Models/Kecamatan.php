<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $connection = 'labkes';

    public $timestamps = false;

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}
