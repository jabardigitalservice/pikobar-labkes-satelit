<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    
    protected $fillable = [
        'nama_depan',
        'nama_belakang',
        'no_ktp',
        'no_sim',
        'no_kk',
        'tanggal_lahir',
        'tempat_lahir',
        'kewarganegaraan',
        'no_hp',
        'no_telp',
        'pekerjaan',
        'jenis_kelamin',
        'kota_id',
        'kecamatan',
        'kelurahan',
        'no_rw',
        'no_rt',
        'alamat_lengkap',
        'keterangan_lain'
    ];

    protected $dates = [
        'tanggal_lahir',
    ];

    public function registers()
    {
        return $this->belongsToMany(Register::class, 'pasien_register', 'pasien_id', 'register_id')
            ->using(PasienRegister::class);
    }
}
