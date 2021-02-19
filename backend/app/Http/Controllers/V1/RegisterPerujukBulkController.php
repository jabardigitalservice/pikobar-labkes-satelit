<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterPerujukBulkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $register_perujuk = $request->get('id');
        $registerPerujuk = RegisterPerujuk::whereIn('id', $register_perujuk)->get();
        foreach ($registerPerujuk->toArray() as $row) {
            DB::beginTransaction();
            try {
                $user = $request->user();
                $register = $this->insertRegister($row, $user);
                $pasien = $this->insertPasien($row, $user);
                $pengambilan_sampel = $this->insertPengambilanSampel($row);
                $this->insertSampel($row, $user, $register, $pengambilan_sampel);
                PasienRegister::create(['pasien_id' => $pasien->id, 'register_id' => $register->id]);
                RegisterPerujuk::find($row['id'])->updateState('diterima');
                return response()->json(['message' => 'success']);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
    }

    private function getNamaRS($fasyankes_id)
    {
        return optional(Fasyankes::find($fasyankes_id))->nama;
    }

    private function getNamaWilayah($wilayah, $id)
    {
        switch ($wilayah) {
            case 'provinsi':
                return optional(Provinsi::find($id))->nama;
                break;
            case 'kota':
                return optional(Kota::find($id))->nama;
                break;
            case 'kecamatan':
                return optional(Kecamatan::find($id))->nama;
                break;
            case 'Kelurahan':
                return optional(Kelurahan::find($id))->nama;
                break;
        }
        return null;
    }

    private function insertRegister($row, $user)
    {
        $namaRS = $this->getNamaRS($row['fasyankes_id']);
        return Register::create($row + [
            'register_perujuk_id' => $row['id'],
            'instansi_pengirim' => $row['fasyankes_pengirim'],
            'nama_rs' => $namaRS,
            'instansi_pengirim_nama' => $namaRS,
            'status' => $row['kriteria'],
            'creator_user_id' => $user->id,
        ]);
    }

    private function insertPasien($row, $user)
    {
        $nama_provinsi = $this->getNamaWilayah('provinsi', $row['provinsi_id']);
        $nama_kota = $this->getNamaWilayah('kota', $row['kota_id']);
        $nama_kecamatan = $this->getNamaWilayah('kecamatan', $row['kecamatan_id']);
        $nama_kelurahan = $this->getNamaWilayah('kelurahan', $row['kelurahan_id']);
        return Pasien::create($row + [
            'register_perujuk_id' => $row['id'],
            'kode_provinsi' => $row['provinsi_id'],
            'kode_kabupaten' => $row['kota_id'],
            'kode_kecamatan' => $row['kecamatan_id'],
            'kode_kelurahan' => $row['kelurahan_id'],
            'nama_provinsi' => $nama_provinsi,
            'nama_kabupaten' => $nama_kota,
            'nama_kecamatan' => $nama_kecamatan,
            'nama_kelurahan' => $nama_kelurahan,
            'kecamatan' => $nama_kecamatan,
            'kelurahan' => $nama_kelurahan,
            'keterangan_lain' => $row['keterangan'],
        ]);
    }

    private function insertSampel($row, $user, $register, $pengambilan_sampel)
    {
        Sampel::create($row + [
            'jenis_sampel_id' => $row['jenis_sampel'],
            'register_perujuk_id' => $row['id'],
            'jenis_sampel_nama' => $row['nama_jenis_sampel'],
            'register_id' => $register->id,
            'pengambilan_sampel_id' => $pengambilan_sampel->id,
            'creator_user_id' => $user->id,
            'sampel_status' => 'sample_taken',
            'waktu_sample_taken' => $row['created_at']
        ]);
    }

    private function insertPengambilanSampel($row)
    {
        return PengambilanSampel::create([
            'sampel_diambil' => false,
            'sampel_diterima' => false,
            'diterima_dari_faskes' => false,
            'sampel_rdt' => false,
            'catatan' => $row['keterangan'],
        ]);
    }
}
