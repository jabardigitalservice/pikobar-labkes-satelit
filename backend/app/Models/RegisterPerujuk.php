<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterPerujuk extends Model
{
    protected $table = 'register_perujuk';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function perujuk()
    {
        return $this->belongsTo(Perujuk::class);
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    public function fasyankes()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function updateState($newstate)
    {
        $this->status = $newstate;
        $this->save();
    }
}
