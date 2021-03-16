<?php

namespace App\Models;

use App\Enums\KriteriaEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Model;

class RegisterPerujuk extends Model
{
    protected $table = 'register_perujuk';

    protected $with = ['kota', 'fasyankes'];

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

    public function scopeRolePerujuk($query, $user)
    {
        $query->when($user->hasRole(RoleEnum::LABORATORIUM()->getIndex()), function ($query) use ($user) {
            $query->where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'dikirim');
        });
        $query->when($user->hasRole(RoleEnum::PERUJUK()->getIndex()), function ($query) use ($user) {
            $query->where('perujuk_id', $user->perujuk_id);
        });
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('nomor_sampel', 'ilike', '%' . $search . '%')
                ->orWhere('nama_pasien', 'ilike', '%' . $search . '%')
                ->orWhereHas('kota', function ($query) use ($search) {
                    $query->where('nama', 'ilike', '%' . $search . '%');
                })
                ->orWhereHas('fasyankes', function ($query) use ($search) {
                    $query->where('nama', 'ilike', '%' . $search . '%');
                })
                ->orWhere('sumber_pasien', 'ilike', '%' . $search . '%')
                ->orwhere('status', 'ilike', '%' . $search . '%');
        });
        return $query;
    }

    public function scopeOrder($query, $order, $order_direction)
    {
        $query->when($order == 'nomor_sampel', function ($query) use ($order_direction) {
            $query->orderBy('nomor_sampel', $order_direction);
        });
        $query->when($order == 'nama_pasien', function ($query) use ($order_direction) {
            $query->orderBy('nama_pasien', $order_direction);
        });
        $query->when($order == 'kota', function ($query) use ($order_direction) {
            $query->whereHas('kota', function ($query) use ($order_direction) {
                $query->orderBy('nama', $order_direction);
            });
        });
        $query->when($order == 'fasyankes', function ($query) use ($order_direction) {
            $query->whereHas('fasyankes', function ($query) use ($order_direction) {
                $query->orderBy('nama', $order_direction);
            });
        });
        $query->when($order == 'kategori', function ($query) use ($order_direction) {
            $query->orderBy('sumber_pasien', $order_direction);
        });
        $query->when($order == 'kriteria', function ($query) use ($order_direction) {
            $query->orderBy('status', $order_direction);
        });
        $query->when($order == 'tanggal', function ($query) use ($order_direction) {
            $query->orderBy('created_at', $order_direction);
        });
        $query->when($order == 'kota', function ($query) use ($order_direction) {
            $query->orderBy('kota', $order_direction);
        });
        return $query;
    }

    public function scopeFilter($query, $params)
    {
        foreach (json_decode($params) as $key => $val) {
            if (!$val) {
                continue;
            }
            $query->filterRegisterPerujuk($key, $val);
        }
        return $query;
    }

    public function scopeFilterRegisterPerujuk($query, $key, $val)
    {
        $query->when($key == 'nomor_sampel', function ($query) use ($val) {
            $query->where('nomor_sampel', 'ilike', '%' . $val . '%');
        });
        $query->when($key == 'nama_pasien', function ($query) use ($val) {
            $query->where(function ($query) use ($val) {
                $query->where('nama_pasien', 'ilike', '%' . $val . '%')
                        ->orWhere('nik', 'ilike', '%' . $val . '%');
            });
        });
        $query->when($key == 'fasyankes', function ($query) use ($val) {
            $query->whereHas('fasyankes', function ($query) use ($val) {
                $query->where('nama', 'ilike', '%' . $val . '%');
            });
        });
        $query->when($key == 'kategori', function ($query) use ($val) {
            $query->where('sumber_pasien', 'ilike', '%' . $val . '%');
        });
        $query->when($key == 'kriteria', function ($query) use ($val) {
            $query->where('status', $val);
        });

        $query->when($key == 'tanggal', function ($query) use ($val) {
            $query->whereDate('created_at', date('Y-m-d', strtotime($val)));
        });
        return $query;
    }
}
