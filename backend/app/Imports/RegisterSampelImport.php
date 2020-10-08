<?php

namespace App\Imports;

use App\Models\Fasyankes;
use App\Models\JenisSampel;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Provinsi;
use App\Models\Register;
use App\Models\Sampel;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegisterSampelImport implements ToCollection, WithHeadingRow
{
    use RegisterTrait;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            foreach ($rows as $key => $row) {
                if (!$row->get('no')) {
                    continue;
                }
                $validator = Validator::make($row->toArray(), [
                    'tgl_masuk_sampel' => 'nullable|date|date_format:Y-m-d',
                    'kode_sampel' => 'required',
                    'kategori' => 'required',
                    'nama' => 'required',
                    'nik' => 'nullable|digits:16',
                    'tgl_lahir' => 'nullable|date|date_format:Y-m-d',
                    'kriteria' => 'nullable|in:Kontak Erat,Suspek,Probable,Konfirmasi,Tanpa Kriteria',
                    'id_fasyankes' => 'required|numeric',
                    'jenis_sampel' => 'required',
                    'swab_ke' => 'nullable|numeric',
                    'tanggal_swab' => 'nullable|date|date_format:Y-m-d',
                ], [
                    'tgl_masuk_sampel.date' => 'Tanggal Masuk Sampel tidak valid',
                    'tgl_masuk_sampel.date_format' => 'Format Tanggal Masuk Sampel harus yyyy-mm-dd',
                    'kode_sampel.required' => 'Nomor Sampel tidak boleh kosong',
                    'kategori.required' => 'Kategori tidak boleh kosong',
                    'nama.required' => 'Nama Pasien Tidak Boleh Kosong',
                    'nik.digits' => 'NIK terdiri dari 16 karakter',
                    'tgl_lahir.date' => 'Tanggal Lahir tidak valid',
                    'tgl_lahir.date_format' => 'Format Tanggal Lahir harus yyyy-mm-dd',
                    'kriteria.in' => 'kriteria harus berisi Kontak Erat, Suspek, Probable, Konfirmasi atau Tanpa Kriteria',
                    'jenis_sampel.required' => 'Jenis Sampel tidak boleh kosong',
                    'id_fasyankes.required' => 'ID Fasyankes tidak boleh kosong',
                    'id_fasyankes.numeric' => 'ID Fasyankes harus berupa angka',
                    'swab_ke.numeric' => 'Swab ke harus berupa angka',
                    'tanggal_swab.date' => 'Tanggal Swab tidak valid',
                    'tanggal_swab.date_format' => 'Format Tanggal Swab harus yyyy-mm-dd',
                ]);

                $validator->after(function ($validator) use ($row) {
                    $user = Auth::user();
                    $nomorsampel = Sampel::where('nomor_sampel', strtoupper($row['kode_sampel']))
                        ->where('lab_satelit_id', $user->lab_satelit_id)->first();
                    if ($nomorsampel) {
                        $validator->errors()->add("kode_sampel", "Nomor Sampel sudah digunakan {$row['kode_sampel']}");
                    }
                })->validate();
                $fasyankes = $this->__getFasyankes($row->get('id_fasyankes'));
                $fasyankesNama = optional($fasyankes)->nama;
                $fasyankesTipe = optional($fasyankes)->tipe;
                $register = new Register;
                $register->nomor_register = generateNomorRegister();
                $register->register_uuid = (string)Str::uuid();
                $register->creator_user_id = $user->id;
                $register->lab_satelit_id = $user->lab_satelit_id;
                $register->fasyankes_id = $row->get('id_fasyankes');
                $register->fasyankes_pengirim = $fasyankesTipe;
                $register->nama_rs = $fasyankesNama;
                $register->instansi_pengirim = $fasyankesTipe;
                $register->instansi_pengirim_nama = $fasyankesNama;
                $register->sumber_pasien = $row->get('kategori');
                $register->status = $row->get('kriteria') ? array_search($row->get('kriteria'), STATUSES) : null;
                $register->swab_ke = $row->get('swab_ke');
                if ($row->get('tanggal_swab')) {
                    $register->tanggal_swab = date('Y-m-d', strtotime($row->get('tanggal_swab')));
                }
                $register->save();

                $pasien = new Pasien;
                $pasien->nama_lengkap = $row->get('nama');
                $pasien->kewarganegaraan = $row->get('kewarganegaraan');
                $pasien->nik = $this->__parseNIK($row->get('nik'));
                if ($row->get('tgl_lahir')) {
                    $pasien->tanggal_lahir = date('Y-m-d', strtotime($row->get('tgl_lahir')));
                }
                $namaProvinsi = $this->__getWilayah('provinsi', $row->get('provinsi_id'));
                $namaKota = $this->__getWilayah('kota', $row->get('kota_id'));
                $namaKecamatan = $this->__getWilayah('kecamatan', $row->get('kecamatan_id'));
                $namaKelurahan = $this->__getWilayah('kelurahan', $row->get('kelurahan_id'));

                $pasien->kode_provinsi = $row->get('provinsi_id');
                $pasien->nama_provinsi = $namaProvinsi;

                $pasien->kota_id = $row->get('kota_id');
                $pasien->kode_kabupaten = $row->get('kode_id');
                $pasien->nama_kabupaten = $namaKota;

                $pasien->kecamatan = $namaKecamatan;
                $pasien->kode_kecamatan = $row->get('kecamatan_id');
                $pasien->nama_kecamatan = $namaKecamatan;

                $pasien->kelurahan = $namaKelurahan;
                $pasien->kode_kelurahan = $row->get('kelurahan_id');
                $pasien->nama_kelurahan = $namaKelurahan;

                $pasien->alamat_lengkap = $row->get('alamat');
                $pasien->sumber_pasien = $row->get('kategori');
                $pasien->no_rt = $row->get('rt');
                $pasien->no_rw = $row->get('rw');
                $pasien->jenis_kelamin = $row->get('jenis_kelamin');
                $pasien->usia_tahun = $row->get('usia_tahun');
                $pasien->usia_bulan = $row->get('usia_bulan');
                $pasien->lab_satelit_id = $user->lab_satelit_id;
                $pasien->save();

                PasienRegister::create([
                    'pasien_id' => $pasien->id,
                    'register_id' => $register->id,
                ]);

                $pengambilan_sampel = PengambilanSampel::create([
                    'sampel_diambil' => false,
                    'sampel_diterima' => false,
                    'diterima_dari_faskes' => false,
                    'sampel_rdt' => false,
                    'catatan' => null,
                ]);

                $sampel = new Sampel();
                $sampel->nomor_sampel = strtoupper($row->get('kode_sampel'));
                $sampel->jenis_sampel_nama = $row->get('jenis_sampel');
                $jenis_sampel = JenisSampel::where('nama', $row->get('jenis_sampel'))->first();
                $sampel->jenis_sampel_id = $row->get('jenis_sampel');
                $sampel->jenis_sampel_id = $jenis_sampel ? $jenis_sampel->id : 999999;
                $sampel->register_id = $register->id;
                $sampel->lab_satelit_id = $user->lab_satelit_id;
                $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
                $sampel->creator_user_id = $user->id;
                $sampel->sampel_status = 'sample_taken';
                if ($row->get('tgl_masuk_sampel')) {
                    $sampel->created_at = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                    $sampel->waktu_sample_taken = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                } else {
                    $sampel->waktu_sample_taken = date('Y-m-d H:i:s');
                }

                $sampel->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    private function __getWilayah($tingkat, $id_wilayah)
    {
        $namaWilayah = null;
        if (!$id_wilayah) {
            return $namaWilayah;
        }
        switch ($tingkat) {
            case 'provinsi':
                $namaWilayah = optional(Provinsi::find($id_wilayah))->nama;
                break;
            case 'kota':
                $namaWilayah = optional(Kota::find($id_wilayah))->nama;
                break;
            case 'kecamatan':
                $namaWilayah = optional(Kecamatan::find($id_wilayah))->nama;
                break;
            case 'kelurahan':
                $namaWilayah = optional(Kelurahan::find($id_wilayah))->nama;
                break;
            default:
                break;
        }
        return $namaWilayah;
    }

    private function __parseNIK($nik)
    {
        if (!$nik) {
            return null;
        }

        if ($separated = explode("'", $nik)) {
            return count($separated) > 1 ? $separated[1] : (string)$nik;
        }

        return (string)$nik;
    }

    private function __getFasyankes($fasyankes)
    {
        return Fasyankes::find($fasyankes);
    }

}
