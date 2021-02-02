<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sampel extends Model
{
    use SoftDeletes;
    protected $table = 'sampel';

    protected $appends = [
        'jenis_sampel',
        'kondisi_sampel'
    ];

    protected $fillable = [
        'nomor_register',
        'fasyankes_id',
        'nomor_rekam_medis',
        'nama_dokter',
        'no_telp',

        'nomor_sampel',
        'jenis_sampel_id',
        'petugas_pengambilan_sampel', // Isinya adalah kondisi sampel, di aliaskan 'kondisi_sampel'
        'tanggal_pengambilan_sampel',
        'waktu_pengambilan_sampel',
        'sampel_status',
        'validator_id',
        'waktu_sample_verified',
        'waktu_sample_valid',
        'valid_file_id',
        'counter_print_hasil'
    ];

    protected $dates = [
        'tanggal_pengambilan_sampel',
        'waktu_sample_verified',
        'waktu_sample_valid'
    ];

    protected $casts = [
        'is_musnah_ekstraksi' => 'boolean',
        'is_musnah_pcr' => 'boolean',
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

    public function updateState($newstate, $options = [], $tanggal = null)
    {
        $arr = array_merge($options, [
            'sampel_id' => $this->id,
            'sampel_status' => $newstate,
            'sampel_status_before' => $this->sampel_status,
        ]);
        $log = SampelLog::create($arr);
        if (empty($this->{'waktu_'.$newstate})) {
            $this->{'waktu_'.$newstate} = date('Y-m-d H:i:s');
            if ($tanggal) {
                $this->{'waktu_'.$newstate} = date('Y-m-d H:i:s', strtotime($tanggal));
            }
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

    public function register()
    {
        return $this->belongsTo(Register::class);
    }

    public function pemeriksaanSampel()
    {
        return $this->hasOne(PemeriksaanSampel::class);
    }

    public function validator()
    {
        return $this->belongsTo(Validator::class);
    }

    public function validFile()
    {
        return $this->belongsTo(File::class, 'valid_file_id');
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->is_musnah_ekstraksi)) {
                $model->is_musnah_ekstraksi = false;
            }
            if (empty($model->is_musnah_pcr)) {
                $model->is_musnah_pcr = false;
            }
        });
    }

    public function getKondisiSampelAttribute()
    {
        return $this->getAttribute('petugas_pengambilan_sampel');
    }

    public function labPCR()
    {
        return $this->belongsTo(LabPCR::class, 'lab_pcr_id');
    }
}
