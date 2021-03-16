<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterSampelDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $register = parent::toArray($request);
        $pasien = $this->pasiens()->first();
        $sampel = $this->sampel;
        return [
            'reg_fasyankes_id' => $register['fasyankes_id'],
            'reg_fasyankes_pengirim' => $register['fasyankes_pengirim'],
            'reg_nama_rs' => $register['nama_rs'],
            'reg_kewarganegaraan' => $pasien->kewarganegaraan,
            'reg_nama_pasien' => $pasien->nama_lengkap,
            'reg_nik' => $pasien->nik,
            'reg_tempatlahir' => $pasien->tempat_lahir,
            'reg_tgllahir' => $pasien->tanggal_lahir,
            'reg_nohp' => $pasien->no_hp,
            'reg_alamat' => $pasien->alamat_lengkap,
            'reg_rt' => $pasien->no_rt,
            'reg_rw' => $pasien->no_rw,
            'reg_jk' => $pasien->jenis_kelamin,
            'reg_usia_tahun' => $pasien->usia_tahun,
            'reg_usia_bulan' => $pasien->usia_bulan,
            'reg_sampel_namadiluarjenis' => $sampel->jenis_sampel_nama,
            'reg_sampel_jenis_sampel' => $sampel->jenis_sampel_id,
            'reg_sampel_nomor' => $sampel->nomor_sampel,
            'reg_status' => $register['status'],
            'reg_swab_ke' => $register['swab_ke'],
            'reg_tanggal_swab' => $register['tanggal_swab'],
            'reg_sumber_pasien' => $register['sumber_pasien'],
            'reg_pelaporan_id' => $pasien->pelaporan_id,
            'reg_pelaporan_id_case' => $pasien->pelaporan_id_case,
            'reg_kode_provinsi' => $pasien->kode_provinsi,
            'reg_nama_provinsi' => $pasien->nama_provinsi,
            'reg_kode_kota' => $pasien->kode_kabupaten,
            'reg_nama_kota' => $pasien->nama_kabupaten,
            'reg_kode_kecamatan' => $pasien->kode_kecamatan,
            'reg_nama_kecamatan' => $pasien->nama_kecamatan,
            'reg_kode_kelurahan' => $pasien->kode_kelurahan,
            'reg_nama_kelurahan' => $pasien->nama_kelurahan,
            'reg_keterangan' => optional($sampel->pengambilan_sampel)->catatan,
            'reg_register' => $register['lab_satelit_id'],
            'reg_pasien' => $pasien->lab_satelit_id,
            'reg_sampel_id' => $sampel->id,
            'reg_keterangan_warganegara' => $pasien->keterangan_warganegara,
            'reg_sampel_status' => $sampel->sampel_status,
            'reg_register_perujuk_id' => $sampel->register_perujuk_id,
        ];
    }
}
