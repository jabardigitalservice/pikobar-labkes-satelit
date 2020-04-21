<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    protected $fillable = [
        'dinkes_city_id',
        'fasyankes_type',
        'fasyankes_id',
        'medical_record_code',
        'doctor_name',
        'phone_number'
    ];
}
