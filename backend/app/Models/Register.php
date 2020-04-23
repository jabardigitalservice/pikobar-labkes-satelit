<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'register';

    protected $fillable = [
        'nomor_register',
        'fasyankes_id',
        'nomor_rekam_medis',
        'nama_dokter',
        'no_telp'
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
}
