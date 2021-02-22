<?php

namespace App\Traits;

/**
 * Trait Search
 */
trait SearchTrait
{
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->searchPasien($search)
                ->searchRegister($search)
                ->searchSampel($search)
                ->searchKota($search);
        });
    }

    public function scopeSearchPasien($query, $search)
    {
        return $query->where('pasien.nama_lengkap', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.nik', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.usia_tahun', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.usia_bulan', 'ilike', '%' . $search . '%');
    }

    public function scopeSearchRegister($query, $search)
    {
        return $query->orwhere('register.instansi_pengirim_nama', 'ilike', '%' . $search . '%')
                    ->orWhere('register.sumber_pasien', 'ilike', '%' . $search . '%')
                    ->orWhere('register.status', 'ilike', '%' . $search . '%');
    }

    public function scopeSearchSampel($query, $search)
    {
        return $query->orwhere('sampel.nomor_sampel', 'ilike', '%' . $search . '%');
    }

    public function scopeSearchKota($query, $search)
    {
        return $query->orwhere('kota.nama', 'ilike', '%' . $search . '%');
    }
}
