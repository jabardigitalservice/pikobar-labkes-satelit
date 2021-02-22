<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait Join
 */
trait JoinTrait
{
    protected static $dbname;

    protected static function bootJoinTrait()
    {
        static::$dbname = config('database.connections.labkes.database');
    }

    public function scopeJoinKota($query)
    {
        return $query->leftJoin(
            DB::raw(
                "dblink('dbname=" . static::$dbname . "', 'select * from kota') AS kota(id int, provinsi_id int, nama varchar)"
            ),
            'kota.id',
            'pasien.kota_id'
        );
    }

    public function scopeJoinProvinsi($query)
    {
        return $query->leftJoin(
            DB::raw(
                "dblink('dbname=" . static::$dbname . "', 'select * from provinsi') AS provinsi(id int, nama varchar)"
            ),
            'provinsi.id',
            'pasien.kode_provinsi'
        );
    }

    public function scopeJoinKecamatan($query)
    {
        return $query->leftJoin(
            DB::raw(
                "dblink('dbname=" . static::$dbname . "', 'select * from kecamatan') AS kecamatan(id int, nama varchar, kota_id int)"
            ),
            'kecamatan.id',
            'pasien.kode_kecamatan'
        );
    }

    public function scopeJoinKelurahan($query)
    {
        return $query->leftJoin(
            DB::raw(
                "dblink('dbname=" . static::$dbname . "', 'select * from kelurahan') AS kelurahan(id int, nama varchar, kecamatan_id int)"
            ),
            'kelurahan.id',
            'pasien.kode_kelurahan'
        );
    }

    public function scopeJoinFasyankes($query)
    {
        return $query->leftJoin(
            DB::raw(
                "dblink('dbname=" . static::$dbname . "', 'select * from fasyankes') AS fasyankes(id int, kota_id int, tipe varchar, nama varchar)"
            ),
            'fasyankes.id',
            'register.fasyankes_id'
        );
    }

    public function scopeJoinPasienRegister($query)
    {
        return $query->leftJoin('pasien_register', 'register.id', 'pasien_register.register_id');
    }

    public function scopeJoinPasien($query)
    {
        return $query->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id');
    }

    public function scopeJoinSampel($query)
    {
        return $query->leftJoin('sampel', 'register.id', 'sampel.register_id')
                        ->whereNull('sampel.deleted_at');
    }
}
