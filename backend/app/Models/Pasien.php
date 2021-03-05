<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'nama_lengkap',
        'kewarganegaraan',
        'nik',
        'tanggal_lahir',
        'tempat_lahir',
        'kode_provinsi',
        'nama_provinsi',
        'kode_kabupaten',
        'nama_kabupaten',
        'kode_kecamatan',
        'nama_kecamatan',
        'kode_kelurahan',
        'nama_kelurahan',
        'kota_id',
        'kecamatan',
        'kelurahan',
        'alamat_lengkap',
        'sumber_pasien',
        'jenis_kelamin',
        'usia_tahun',
        'usia_bulan',
        'lab_satelit_id',
        'perujuk_id',
        'register_perujuk_id',
        'no_hp',
        'no_rw',
        'no_rt',
        'keterangan_lain',
        'kode_kasus',
        //other
        'no_telp',
        'pekerjaan',
        'suhu',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d',
    ];

    public function registers()
    {
        return $this->belongsToMany(Register::class, 'pasien_register', 'pasien_id', 'register_id')
            ->using(PasienRegister::class);
    }

    public function gejalaPasien()
    {
        return $this->belongsToMany(Register::class, 'gejala_pasien', 'pasien_id', 'register_id')
            ->using(GejalaPasien::class)
            ->withPivot([
                'pasien_rdt',
                'hasil_rdt_positif',
                'tanggal_rdt',
                'keterangan_rdt',
                'tanggal_onset_gejala',
                'daftar_gejala',
                'gejala_lain',
            ]);
    }

    public function pemeriksaanPenunjang()
    {
        return $this->belongsToMany(Register::class, 'pemeriksaan_penunjang', 'pasien_id', 'register_id')
            ->using(PemeriksaanPenunjang::class)
            ->withPivot([
                'xray_paru',
                'penjelasan_xray',
                'leukosit',
                'limfosit',
                'trombosit',
                'ventilator',
                'status_kesehatan', // pulang, dirawat, meninggal
                'keterangan_lab'
            ]);
    }

    public function riwayatKontak()
    {
        return $this->belongsToMany(Register::class, 'riwayat_kontak', 'pasien_id', 'register_id')
            ->using(RiwayatKontak::class)
            ->withPivot(
                'nama_lengkap',
                'alamat',
                'hubungan',
                'tanggal_awal',
                'tanggal_akhir',
                'positif_covid19',
                'keluarga_sakit_sejenis'
            );
    }

    public function riwayatLawatan()
    {
        return $this->belongsToMany(Register::class, 'riwayat_lawatan', 'pasien_id', 'register_id')
            ->using(RiwayatLawatan::class)
            ->withPivot(
                'tanggal_lawatan',
                'nama_kota',
                'nama_negara'
            );
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}
