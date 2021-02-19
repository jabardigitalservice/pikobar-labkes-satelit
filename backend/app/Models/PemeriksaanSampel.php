<?php

namespace App\Models;

use App\Traits\PemeriksaanTrait;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanSampel extends Model
{
    use PemeriksaanTrait;

    protected $table = 'pemeriksaansampel';

    protected $fillable = [
        'sampel_id',
        'user_id',
        'tanggal_input_hasil',
        'catatan_pemeriksaan',
        'kesimpulan_pemeriksaan',
        'hasil_deteksi',
        //other
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
        'grafik',
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

    public function setHasilDeteksiAttribute($value)
    {
        return $this->attributes['hasil_deteksi'] = is_array($value) ? json_encode($value) : $value;
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }
}
