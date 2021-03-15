<?php

namespace App\Models;

use App\Enums\KriteriaEnum;
use Illuminate\Database\Eloquent\Model;

class RegisterPerujuk extends Model
{
    protected $table = 'register_perujuk';

    protected $fillable = [
        'nomor_register',
        'register_uuid',
        'creator_user_id',
        'lab_satelit_id',
        'kode_kasus',
        'perujuk_id',
        'sumber_pasien',
        'kriteria',
        'swab_ke',
        'tanggal_swab',
        'nomor_sampel',
        'jenis_sampel',
        'nama_jenis_sampel',
        'fasyankes_id',
        'fasyankes_pengirim',
        'nama_pasien',
        'kewarganegaraan',
        'keterangan_warganegara',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'kelurahan_id',
        'alamat',
        'no_rt',
        'no_rw',
        'jenis_kelamin',
        'keterangan',
        'usia_tahun',
        'usia_bulan',
        'created_at'
    ];

    public function perujuk()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    public function fasyankes()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function updateState($newstate)
    {
        $this->status = $newstate;
        $this->save();
    }

    public function setKriteriaAttibute($value)
    {
        $this->attributes['kriteria'] = KriteriaEnum::make($value)->getIndex();
    }
}
