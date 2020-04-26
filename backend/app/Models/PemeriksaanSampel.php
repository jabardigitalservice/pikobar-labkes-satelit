<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanSampel extends Model
{
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
    ];

    protected $casts = [
        'target_gen' => 'array',
        'hasil_deteksi' => 'array',
    ];
}
