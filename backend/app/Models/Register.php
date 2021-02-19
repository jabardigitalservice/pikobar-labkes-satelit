<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use SoftDeletes;

    protected $table = 'register';

    protected $fillable = [
        'nomor_register',
        'register_uuid',
        'creator_user_id',
        'lab_satelit_id',
        'fasyankes_id',
        'fasyankes_pengirim',
        'nama_rs',
        'instansi_pengirim',
        'instansi_pengirim_nama',
        'sumber_pasien',
        'status',
        'swab_ke',
        'tanggal_swab',
        'perujuk_id',
        'register_perujuk_id',
        //other
        'nomor_rekam_medis',
        'nama_dokter',
        'no_telp',
        'jenis_registrasi',
        'tanggal_kunjungan',
        'kunjungan_ke',
        'rs_kunjungan',
        'dinkes_pengirim',
        'other_dinas_pengirim',
        'other_nama_rs',
        'hasil_rdt',
    ];

    protected $hidden = ['fasyankes_id'];

    protected $dates = [
        'tanggal_kunjungan'
    ];

    public function fasyankes()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function pasiens()
    {
        return $this->belongsToMany(Pasien::class, 'pasien_register', 'register_id', 'pasien_id')
            ->using(PasienRegister::class);
    }

    public function pengambilanSampel()
    {
        return $this->belongsToMany(PengambilanSampel::class, 'pengambilan_sampel_registrasi', 'register_id', 'pengambilan_sampel_id');
    }

    public function sampel()
    {
        return $this->hasOne(Sampel::class);
    }

    public function logs()
    {
        return $this->hasMany(RegisterLog::class, 'register_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($register) {
            if ($register->sampel) {
                $register->sampel()->delete();
            }
        });
    }
}
