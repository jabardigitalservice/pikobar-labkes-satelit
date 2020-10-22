<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'email',
        'token',
        'lab_satelit_id'
    ];
}
