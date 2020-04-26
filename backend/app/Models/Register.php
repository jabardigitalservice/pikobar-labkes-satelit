<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use SoftDeletes;
    protected $table = 'register';
    protected $appends = ['jenis_sampel'];

    protected $fillable = [
        'nomor_register',
        'fasyankes_id',
        'nomor_rekam_medis',
        'nama_dokter',
        'no_telp'
    ];

    protected $hidden = ['fasyankes_id'];

    public function fasyankes()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusRegister::class, 'register_status');
    }

    public function ekstraksi()
    {
        return $this->hasOne(Ekstraksi::class, 'register_id');
    }

    public function pcr()
    {
        return $this->hasOne(PemeriksaanSampel::class, 'register_id');
    }

    public function logs()
    {
        return $this->hasMany(RegisterLog::class);
    }

    public function pasiens()
    {
        return $this->belongsToMany(Pasien::class, 'pasien_register', 'register_id', 'pasien_id')
            ->using(PasienRegister::class);
    }

    public function riwayatKunjungan()
    {
        return $this->hasOne(RiwayatKunjungan::class);
    }

    public function gejalaPasien()
    {
        return $this->belongsToMany(Pasien::class, 'gejala_pasien', 'register_id', 'pasien_id')
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
        return $this->belongsToMany(Pasien::class, 'pemeriksaan_penunjang', 'register_id', 'pasien_id')
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
        return $this->belongsToMany(Pasien::class, 'riwayat_kontak', 'register_id', 'pasien_id')
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
        return $this->belongsToMany(Pasien::class, 'riwayat_lawatan', 'register_id', 'pasien_id')
            ->using(RiwayatLawatan::class)
            ->withPivot(
                'tanggal_lawatan',
                'nama_kota',
                'nama_negara'
            );
    }

    public function riwayatPenyakitPenyerta()
    {
        return $this->hasOne(RiwayatPenyakitPenyerta::class);

        // Many to Many
        // return $this->belongsToMany(Pasien::class, 'riwayat_penyakit_penyerta', 'register_id', 'pasien_id')
        //     ->using(RiwayatPenyakitPenyerta::class)
        //     ->withPivot(
        //         'daftar_penyakit'
        //     );
    }

    public function getJenisSampelAttribute()
    {
        return "Usap Nasofaring & Orofaring";
    }

    public function updateState($newstate, $options = [])
    {
        $arr = array_merge($options, [
            'register_id' => $this->id,
            'register_status' => $newstate,
            'register_status_before' => $this->register_status,
        ]);
        $log = RegisterLog::create($arr);
        if (empty($this->{'waktu_'.$newstate})) {
            $this->{'waktu_'.$newstate} = date('Y-m-d H:i:s');
        }
        $this->register_status = $newstate;
        $this->save();
    }

    public function addLog($options = [])
    {
        $arr = array_merge($options, [
            'register_id' => $this->id,
            'register_status' => $this->register_status,
            'register_status_before' => $this->register_status,
        ]);
        $log = RegisterLog::create($arr);
    }

}
