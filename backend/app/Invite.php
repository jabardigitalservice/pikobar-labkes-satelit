<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'token',
        'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
