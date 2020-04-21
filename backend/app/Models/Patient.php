<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'no_ktp',
        'birthdate',
        'mobile_phone',
        'full_address'
    ];

    protected $dates = [
        'birthdate'
    ];
    
}
