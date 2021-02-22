<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * Trait Filter
 */
trait FilterTrait
{
    public function scopeFilter($query, $params)
    {
        foreach (json_decode($params) as $key => $val) {
            if ($val) {
                $query->filterPasien($key, $val)
                        ->filterRegister($key, $val)
                        ->filterSampel($key, $val);
            }
        }
        return $query;
    }

    public function scopeFilterPasien($query, $key, $val)
    {
        $query->when($key == 'nama_pasien', function ($query) use ($val) {
            return $query->where(function ($query) use ($val) {
                return $query->where('pasien.nama_lengkap', 'ilike', '%' . $val . '%')
                                ->orWhere('pasien.nik', 'ilike', '%' . $val . '%');
            });
        });
        $query->when($key == 'kota', function ($query) use ($val) {
            return $query->where('pasien.kota_id', $val);
        });
        return $query;
    }

    public function scopeFilterSampel($query, $key, $val)
    {
        $query->when($key == 'start_date', function ($query) use ($val) {
            return $query->whereDate('sampel.waktu_sample_taken', '>=', Carbon::parse($val));
        });
        $query->when($key == 'end_date', function ($query) use ($val) {
            return $query->whereDate('sampel.waktu_sample_taken', '<=', Carbon::parse($val));
        });
        $query->when($key == 'nomor_sampel', function ($query) use ($val) {
            return $query->where('sampel.nomor_sampel', 'ilike', '%' . $val . '%');
        });
        return $query;
    }

    public function scopeFilterRegister($query, $key, $val)
    {
        $query->when($key == 'fasyankes_id', function ($query) use ($val) {
            return $query->where('register.fasyankes_id', $val);
        });
        $query->when($key == 'sumber_pasien', function ($query) use ($val) {
            return $query->where('register.sumber_pasien', 'ilike', '%' . $val . '%');
        });
        $query->when($key == 'status', function ($query) use ($val) {
            return $query->where('register.status', 'ilike', '%' . $val . '%');
        });
        return $query;
    }
}
