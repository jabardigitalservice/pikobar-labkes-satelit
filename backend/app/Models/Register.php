<?php

namespace App\Models;

use App\Enums\KriteriaEnum;
use App\Traits\FilterTrait;
use App\Traits\JoinTrait;
use App\Traits\OrderTrait;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use SoftDeletes;
    use JoinTrait;
    use SearchTrait;
    use FilterTrait;
    use OrderTrait;

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

    public function setKriteriaAttribute($value)
    {
        $this->attributes['status'] = $value ? KriteriaEnum::make($value)->getIndex() : null;
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

    public function scopeSelectCustom($query)
    {
        return $query->select(
            'nik',
            'nama_lengkap',
            'tanggal_lahir',
            'usia_tahun',
            'nomor_sampel',
            'kota.nama as nama_kota',
            'pasien_register.*',
            'register.sumber_pasien',
            'status',
            'waktu_sample_taken',
            'nama_rs',
            'sampel_status',
            'sampel.register_perujuk_id'
        );
    }

    public function scopeLabSatelit($query, $labSatelitId)
    {
        return $query->where('register.lab_satelit_id', $labSatelitId)
                        ->where('sampel.lab_satelit_id', $labSatelitId)
                        ->where('pasien.lab_satelit_id', $labSatelitId);
    }
}
