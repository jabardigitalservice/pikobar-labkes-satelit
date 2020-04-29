<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
    protected $table = 'validator';

    protected $fillable = [
        'nama',
        'nip',
        'is_active'
    ];

    public function sampel()
    {
        return $this->hasMany(Sampel::class);
    }
}
