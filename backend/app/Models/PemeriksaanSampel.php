<?php

namespace App\Models;

use App\Traits\PemeriksaanTrait;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanSampel extends Model
{
    use PemeriksaanTrait;

    protected $table = 'pemeriksaansampel';

    protected $fillable = [
        'tanggal_penerimaan_sampel',
        'jam_penerimaan_sampel',
        'lab_penerima_sampel',
        'lab_penerima_sampel_lainnya',
        'petugas_penerima_sampel_rna',
        'operator_real_time_pcr',
        'tanggal_mulai_pemeriksaan',
        'jam_mulai_pcr',
        'jam_selesai_pcr',
        'metode_pemeriksaan',
        'nama_kit_pemeriksaan',
        'target_gen',
        'hasil_deteksi',
        'grafik',
        'kesimpulan_pemeriksaan',
        'catatan_pemeriksaan'
    ];

    protected $casts = [
        'target_gen' => 'array',
        'hasil_deteksi' => 'array',
        'grafik' => 'array',
    ];

    protected $appends = [
        'hasil_deteksi_parsed',
    ];

    public function sampel()
    {
        return $this->belongsTo(Sampel::class);
    }

    public function getHasilDeteksiParsedAttribute()
    {
        return $this->parseHasilDeteksi($this->getAttribute('hasil_deteksi'));
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }
}
