<?php

namespace App\Models\Labkes;

use Illuminate\Database\Eloquent\Model;

class Sampel extends Model
{
    protected $table = 'sampel';

    protected $connection = 'labkes';

    public function scopePemeriksaanSampel($query)
    {
        return $query->leftJoin('pemeriksaansampel', 'pemeriksaansampel.sampel_id', 'sampel.id')
                        ->where('pemeriksaansampel.is_from_migration', false);
    }

    public function scopeRegister($query)
    {
        return $query->leftJoin('register', 'register.id', 'sampel.register_id')
                        ->where('register.is_from_migration', false)
                        ->whereNull('register.deleted_at');
    }

    public function scopePasienRegister($query)
    {
        return $query->leftJoin('pasien_register', 'pasien_register.register_id', 'sampel.register_id')
                        ->where('pasien_register.is_from_migration', false);
    }

    public function scopePasien($query)
    {
        return $query->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
                        ->where('pasien.is_from_migration', false);
    }

    public function scopeWilayah($query)
    {
        return $query->leftJoin('kota', 'pasien.kota_id', 'kota.id')
                        ->leftJoin('provinsi', 'pasien.provinsi_id', 'provinsi.id')
                        ->leftJoin('kecamatan', 'pasien.kecamatan_id', 'kecamatan.id')
                        ->leftJoin('kelurahan', 'pasien.kelurahan_id', 'kelurahan.id');
    }

    public function scopeIsFromMigration($query)
    {
        return $query->where('sampel.is_from_migration', false);
    }

    public function scopeSampel($query,  $sampel_status)
    {
        $sampel_status = is_array($sampel_status) ? $sampel_status : [$sampel_status];
        return $query->whereIn('sampel_status', $sampel_status)
                ->isFromMigration()
                ->pemeriksaanSampel()
                ->register()
                ->pasienRegister()
                ->pasien()
                ->wilayah();
    }

    public function scopeSelectCostum($query)
    {
        return $query->select(
            "register.nomor_register",
            "register.register_uuid",
            "register.fasyankes_id",
            "register.fasyankes_pengirim",
            "register.nama_rs",
            "register.sumber_pasien",
            "pasien.status",
            "register.kunjungan_ke",
            "register.tanggal_kunjungan",
            "pasien.nama_lengkap",
            "pasien.kewarganegaraan",
            "pasien.nik",
            "pasien.tanggal_lahir",
            "provinsi.id AS provinsi_id",
            "provinsi.nama AS provinsi_nama",
            "kota.id AS kota_id",
            "kota.nama AS kota_nama",
            "kecamatan.id AS kecamatan_id",
            "kecamatan.nama AS kecamatan_nama",
            "kelurahan.id AS kelurahan_id",
            "kelurahan.nama AS kelurahan_nama",
            "pasien.alamat_lengkap",
            "pasien.jenis_kelamin",
            "pasien.usia_tahun",
            "pasien.usia_bulan",
            "sampel.nomor_sampel",
            "sampel.jenis_sampel_id",
            "sampel.jenis_sampel_nama",
            "sampel.sampel_status",
            "sampel.waktu_sample_taken",
            "sampel.waktu_pcr_sample_analyzed",
            "sampel.waktu_sample_valid",
            "pemeriksaansampel.kesimpulan_pemeriksaan",
            "pemeriksaansampel.catatan_pemeriksaan",
            "pemeriksaansampel.catatan_penerimaan"
        );
    }
}
