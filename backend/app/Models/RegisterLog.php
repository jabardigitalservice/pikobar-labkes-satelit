<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterLog extends Model
{
    protected $fillable = [
        'info',
        'user_id'
    ];

    protected $hidden = [
        "info->'updated_at'"
    ];

    public function register()
    {
        return $this->belongsTo('App\Models\Register', 'register_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
