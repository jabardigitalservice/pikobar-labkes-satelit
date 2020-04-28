<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sampel extends Model
{
    use SoftDeletes;
    protected $table = 'sampel';
    protected $appends = ['jenis_sampel'];

    protected $fillable = [
        'nomor_register',
        'fasyankes_id',
        'nomor_rekam_medis',
        'nama_dokter',
        'no_telp',

        'nomor_sampel',
        'jenis_sampel_id',
        'petugas_pengambilan_sampel',
        'tanggal_pengambilan_sampel',
        'waktu_pengambilan_sampel',
        'sampel_status',
    ];

    public function fasyankes()
    {
        return $this->belongsTo(Fasyankes::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusSampel::class, 'sampel_status');
    }

    public function ekstraksi()
    {
        return $this->hasOne(Ekstraksi::class, 'sampel_id');
    }

    public function pcr()
    {
        return $this->hasOne(PemeriksaanSampel::class, 'sampel_id');
    }

    public function logs()
    {
        return $this->hasMany(SampelLog::class);
    }

    public function getJenisSampelAttribute()
    {
        return $this->jenis_sampel_nama;
    }

    public function updateState($newstate, $options = [])
    {
        $arr = array_merge($options, [
            'sampel_id' => $this->id,
            'sampel_status' => $newstate,
            'sampel_status_before' => $this->sampel_status,
        ]);
        $log = SampelLog::create($arr);
        if (empty($this->{'waktu_'.$newstate})) {
            $this->{'waktu_'.$newstate} = date('Y-m-d H:i:s');
        }
        $this->sampel_status = $newstate;
        $this->save();
    }

    public function addLog($options = [])
    {
        $arr = array_merge($options, [
            'sampel_id' => $this->id,
            'sampel_status' => $this->sampel_status,
            'sampel_status_before' => $this->sampel_status,
        ]);
        $log = SampelLog::create($arr);
    }

}