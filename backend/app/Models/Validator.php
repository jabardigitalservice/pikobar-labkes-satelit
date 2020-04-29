<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Validator extends Model
{
    use SoftDeletes;

    protected $table = 'validator';

    protected $fillable = [
        'nama',
        'nip',
        'is_active'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function sampel()
    {
        return $this->hasMany(Sampel::class);
    }
}
