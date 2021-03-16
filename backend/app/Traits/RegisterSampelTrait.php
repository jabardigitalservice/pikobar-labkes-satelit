<?php

namespace App\Traits;

use App\Enums\JenisSampelEnum;
use App\Models\JenisSampel;
use Illuminate\Support\Str;

/**
 * Register Sampel trait
 *
 */
trait RegisterSampelTrait
{
    private function getRequestRegister($request)
    {
        return [
            'fasyankes_id' => $request->reg_fasyankes_id,
            'fasyankes_pengirim' => $request->reg_fasyankes_pengirim,
            'nama_rs' => $request->reg_nama_rs,
            'instansi_pengirim_nama' => $request->reg_nama_rs,
            'instansi_pengirim' => $request->reg_fasyankes_pengirim,
            'sumber_pasien' => $request->reg_sumber_pasien,
            'status' => $request->reg_status,
            'swab_ke' => $request->reg_swab_ke,
            'tanggal_swab' => $request->reg_tanggal_swab,
        ];
    }

    private function getNomorRegister()
    {
        return [
            'nomor_register' => generateNomorRegister(),
            'register_uuid' => Str::uuid(),
        ];
    }

    private function getRequestPasien($request)
    {
        return [
            'nama_lengkap' => $request->reg_nama_pasien,
            'kewarganegaraan' => $request->reg_kewarganegaraan,
            'keterangan_warganegara' => $request->reg_keterangan_warganegara,
            'nik' => $request->reg_nik,
            'tempat_lahir' => $request->reg_tempatlahir,
            'tanggal_lahir' => $request->reg_tgllahir,
            'no_hp' => $request->reg_nohp,
            'kode_provinsi' => $request->reg_kode_provinsi,
            'nama_provinsi' => $request->reg_nama_provinsi,
            'kota_id' => $request->reg_kode_kota,
            'kecamatan' => $request->reg_nama_kecamatan,
            'kelurahan' => $request->reg_nama_kelurahan,
            'kode_kabupaten' => $request->reg_kode_kota,
            'nama_kabupaten' => $request->reg_nama_kota,
            'kode_kecamatan' => $request->reg_kode_kecamatan,
            'nama_kecamatan' => $request->reg_nama_kecamatan,
            'kode_kelurahan' => $request->reg_kode_kelurahan,
            'nama_kelurahan' => $request->reg_nama_kelurahan,
            'alamat_lengkap' => $request->reg_alamat,
            'sumber_pasien' => $request->reg_sumber_pasien,
            'no_rt' => $request->reg_rt,
            'no_rw' => $request->reg_rw,
            'jenis_kelamin' => $request->reg_jk,
            'keterangan_lain' => $request->reg_keterangan,
            'usia_tahun' => $request->reg_usia_tahun,
            'usia_bulan' => $request->reg_usia_bulan,
            'pelaporan_id' => $request->reg_pelaporan_id,
            'pelaporan_id_case' => $request->pelaporan_id_case,
        ];
    }

    private function getRequestPengambilanSampel($request)
    {
        return [
            'sampel_diambil' => false,
            'sampel_diterima' => false,
            'diterima_dari_faskes' => false,
            'sampel_rdt' => false,
            'catatan' => $request->reg_keterangan,
        ];
    }

    private function getRequestSampel($request, $register, $pengambilan_sampel)
    {
        $jenis_sampel_nama = $request->reg_sampel_namadiluarjenis;
        if ($request->reg_sampel_jenis_sampel != JenisSampelEnum::LAINNYA()->getIndex()) {
            $jenis_sampel_nama = JenisSampel::where('id', $request->reg_sampel_jenis_sampel)->first()->nama;
        }
        return [
            'nomor_sampel' => $request->reg_sampel_nomor,
            'jenis_sampel_id' => $request->reg_sampel_jenis_sampel,
            'jenis_sampel_nama' => $jenis_sampel_nama,
            'register_id' => $register->id,
            'pengambilan_sampel_id' => $pengambilan_sampel->id,
        ];
    }

    private function getSampelStatus()
    {
        return [
            'sampel_status' => 'sample_taken',
            'waktu_sample_taken' => now()
        ];
    }

    private function getUser($request)
    {
        $user = $request->user();
        return [
            'creator_user_id' => $user->id,
            'lab_satelit_id' => $user->lab_satelit_id
        ];
    }

    private function getLogsRegister($registerOrigin, $registerChanges)
    {
        $registerLogs = [];
        foreach ($registerChanges as $key => $value) {
            if ($key != "updated_at") {
                $registerLogs[$key]["from"] = $key == 'status' && $registerOrigin[$key] ? STATUSES[$registerOrigin[$key]] : $registerOrigin[$key];
                $registerLogs[$key]["to"] = $key == 'status' ? STATUSES[$value] : $value;
            }
        }
        return $registerLogs;
    }

    private function getLogsPasien($pasienOrigin, $pasienChanges)
    {
        $pasienLogs = [];
        foreach ($pasienChanges as $key => $value) {
            if ($key != "updated_at") {
                $pasienLogs[$key]["from"] = $key == 'tanggal_lahir' ? date('d-m-Y', strtotime($pasienOrigin[$key])) : $pasienOrigin[$key];
                $pasienLogs[$key]["to"] = $key == 'tanggal_lahir' ? date('d-m-Y', strtotime($value)) : $value;
            }
        }
        return $pasienLogs;
    }

    private function getLogsSampel($sampelOrigin, $sampelChanges)
    {
        $sampelLogs = [];
        foreach ($sampelChanges as $key => $value) {
            if ($key != "updated_at") {
                $sampelLogs[$key]["from"] = $sampelOrigin[$key];
                $sampelLogs[$key]["to"] = $value;
            }
        }
        return $sampelLogs;
    }
}
