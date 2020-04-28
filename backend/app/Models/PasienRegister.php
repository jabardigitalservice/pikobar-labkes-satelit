<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PasienRegister extends Pivot
{
    protected $table = 'pasien_register';

    protected $fillable = [
        'pasien_id',
        'register_id'
    ];

    public $timestamps = false;
}
