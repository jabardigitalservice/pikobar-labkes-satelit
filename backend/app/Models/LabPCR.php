<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabPCR extends Model
{
    protected $table = 'lab_pcr';

    protected $fillable = [
        'nama',
    ];

    public function sampel()
    {
        return $this->hasMany(Sampel::class);
    }

}
