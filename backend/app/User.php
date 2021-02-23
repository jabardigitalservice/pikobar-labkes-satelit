<?php

namespace App;

use App\Enums\UserStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Kota;
use App\Models\Sampel;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role_id',
        'koordinator',
        'lab_satelit_id',
        'kota_id',
        'status',
        'invited_at',
        'perujuk_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => UserStatusEnum::class . '|nullable',
        'role_id' => RoleEnum::class . '|nullable,integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '.jpg?s=200&d=mm';
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsTo('App\Role', 'role_id', 'id');
    }

    public function lab_satelit()
    {
        return $this->belongsTo('App\Models\LabSatelit', 'lab_satelit_id', 'id');
    }

    public function perujuk()
    {
        return $this->belongsTo('App\Models\Fasyankes', 'perujuk_id', 'id');
    }

    public function registerLogs()
    {
        return $this->hasMany('App\Models\RegisterLog', 'user_id');
    }

    public function pemeriksaanSampels()
    {
        return $this->hasMany('App\Models\PemeriksaanSampel', 'user_id');
    }


    //check if has any relationship, return true
    public function getHasDataAttribute()
    {
        return $this->registerLogs()->exists() || $this->pemeriksaanSampels()->exists();
    }

    public function getHasDataPerujukAttribute()
    {
        return Sampel::where('perujuk_id', $this->perujuk_id)->exists();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function scopeUserDinkes($query)
    {
        return $query->where('users.role_id', RoleEnum::DINKES()->getIndex());
    }

    public function scopeUserLab($query)
    {
        return $query->where('users.role_id', RoleEnum::LABORATORIUM()->getIndex());
    }

    public function scopeUserPerujuk($query)
    {
        return $query->where('users.role_id', RoleEnum::PERUJUK()->getIndex());
    }

    public function dinkes()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'id');
    }

    public function invite()
    {
        return $this->hasOne(Invite::class, 'email', 'email');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($user) {
            if ($user->invite) {
                $user->invite()->delete();
            }
        });
    }

    public function hasRole($roleIndex): bool
    {
        return $this->role_id == $roleIndex;
    }
}
