<?php

namespace App\Models\Labkes;

use App\Models\Kota;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $connection = 'labkes';

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}
