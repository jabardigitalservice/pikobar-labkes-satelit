<?php

namespace App\Imports;

use App\Models\Fasyankes;
use App\Models\JenisSampel;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PemeriksaanSampel;
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
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class HasilPemeriksaanAkhirImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
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
                    'tgl_masuk_sampel' => 'required|date|date_format:Y-m-d',
                    'kode_sampel' => 'required',
                    'kategori' => 'required',
                    'nama' => 'required',
                    'nik' => 'nullable|digits:16',
                    'tgl_lahir' => 'nullable|date|date_format:Y-m-d',
                    'kriteria' => 'nullable|in:Kontak Erat,Suspek,Probable,Konfirmasi,Tanpa Kriteria',
                    'kode_instansi' => 'required|numeric',
                    'jenis_sampel' => 'required',
                    'swab_ke' => 'nullable|numeric',
                    'tanggal_swab' => 'nullable|date|date_format:Y-m-d',
                    'interpretasi' => 'required|in:Positif,Negatif,Inkonklusif',
                    'tanggal_pemeriksaan' => 'required|date|date_format:Y-m-d',
                ], [
                    'tgl_masuk_sampel.required' => 'Tanggal masuk sampel tidak boleh kosong',
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
                    'kode_instansi.required' => 'Kode Instansi tidak boleh kosong',
                    'kode_instansi.numeric' => 'Kode Instansi harus berupa angka',
                    'swab_ke.numeric' => 'Swab ke harus berupa angka',
                    'tanggal_swab.date' => 'Tanggal Swab tidak valid',
                    'tanggal_swab.date_format' => 'Format Tanggal Swab harus yyyy-mm-dd',
                    'interpretasi.required' => 'Interpretasi tidak boleh kosong',
                    'interpretasi.in' => 'Interpretasi tidak valid harus Positif,Negatif,Inkonklusif',
                    'tanggal_pemeriksaan.date' => 'Tanggal Pemeriksaan tidak valid',
                    'tanggal_pemeriksaan.date_format' => 'Format Tanggal Pemeriksaan harus yyyy-mm-dd',
                ]);

                $validator->after(function ($validator) use ($row) {
                    $user = Auth::user();
                    $nomorsampel = Sampel::where('nomor_sampel', strtoupper($row['kode_sampel']))
                        ->where('lab_satelit_id', $user->lab_satelit_id)->first();
                    if ($nomorsampel) {
                        $validator->errors()->add("kode_sampel", "Nomor Sampel sudah digunakan {$row['kode_sampel']}");
                    }
                    $fasyankes = $this->__getFasyankes($row->get('kode_instansi'));
                    if (!$fasyankes) {
                        $validator->errors()->add("kode_instansi", "Kode Instansi tidak ditemukan {$row['kode_instansi']}");
                    }
                })->validate();
                $fasyankes = $this->__getFasyankes($row->get('kode_instansi'));
                $fasyankesId = optional($fasyankes)->id;
                $fasyankesNama = optional($fasyankes)->nama;
                $fasyankesTipe = optional($fasyankes)->tipe;
                $register = new Register;
                $register->nomor_register = generateNomorRegister();
                $register->register_uuid = (string)Str::uuid();
                $register->creator_user_id = $user->id;
                $register->lab_satelit_id = $user->lab_satelit_id;
                $register->fasyankes_id = $fasyankesId;
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
                $provinsi = $this->__getWilayah('provinsi', $row->get('kode_provinsi'));
                $kota = $this->__getWilayah('kota', $row->get('kode_kotakab'));
                $kecamatan = $this->__getWilayah('kecamatan', $row->get('kode_kecamatan'));
                $kelurahan = $this->__getWilayah('kelurahan', $row->get('kode_kelurahan'));

                $namaProvinsi = optional($provinsi)->nama;
                $namaKota = optional($kota)->nama;
                $namaKecamatan = optional($kecamatan)->nama;
                $namaKelurahan = optional($kelurahan)->nama;

                $kodeProvinsi = optional($provinsi)->id;
                $kodeKota = optional($kota)->id;
                $kodeKecamatan = optional($kecamatan)->id;
                $kodeKelurahan = optional($kelurahan)->id;

                $pasien->kode_provinsi = $kodeProvinsi;
                $pasien->nama_provinsi = $namaProvinsi;

                $pasien->kota_id = $kodeKota;
                $pasien->kode_kabupaten = $kodeKota;
                $pasien->nama_kabupaten = $namaKota;

                $pasien->kecamatan = $namaKecamatan;
                $pasien->kode_kecamatan = $kodeKecamatan;
                $pasien->nama_kecamatan = $namaKecamatan;

                $pasien->kelurahan = $namaKelurahan;
                $pasien->kode_kelurahan = $kodeKelurahan;
                $pasien->nama_kelurahan = $namaKelurahan;

                $pasien->alamat_lengkap = $row->get('alamat');
                $pasien->sumber_pasien = $row->get('kategori');
                $pasien->jenis_kelamin = $row->get('jenis_kelamin');
                $pasien->usia_tahun = $row->get('usia');
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
                $sampel->created_at = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                $sampel->waktu_sample_taken = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s', strtotime($row->get('tanggal_pemeriksaan') . ' ' . date('H:i:s')));
                $sampel->save();

                $pcr = new PemeriksaanSampel;
                $pcr->sampel_id = $sampel->id;
                $pcr->user_id = $user->id;
                $pcr->tanggal_input_hasil = date('Y-m-d', strtotime($row->get('tanggal_pemeriksaan')));
                $pcr->catatan_pemeriksaan = $row->get('keterangan') != '' ? $row->get('keterangan') : null;
                $pcr->kesimpulan_pemeriksaan = strtolower($row->get('interpretasi'));
                $pcr->save();

                $sampel->updateState('pcr_sample_analyzed', [
                    'user_id' => $user->id,
                    'metadata' => $pcr,
                    'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
                ]);
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
                $namaWilayah = Provinsi::find($id_wilayah);
                break;
            case 'kota':
                $namaWilayah = Kota::find($id_wilayah);
                break;
            case 'kecamatan':
                $namaWilayah = Kecamatan::find($id_wilayah);
                break;
            case 'kelurahan':
                $namaWilayah = Kelurahan::find($id_wilayah);
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

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
