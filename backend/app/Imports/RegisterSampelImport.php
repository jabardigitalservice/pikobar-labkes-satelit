<?php

namespace App\Imports;

use App\Models\JenisSampel;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\Sampel;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
                $v = Validator::make($row->toArray(), [
                    'nama_instansi' => 'required',
                    'instansi_pengirim' => 'required',
                    'tgl_masuk_sampel' => 'date|date_format:Y-m-d',
                    'nik' => 'nullable|digits:16',
                    'nama' => 'required',
                    'tgl_lahir' => 'nullable|date|date_format:Y-m-d',
                    'kode_sampel' => 'required',
                    'jenis_sampel' => 'required',
                    'swab_ke' => 'nullable|numeric',
                    'tanggal_swab' => 'nullable|date|date_format:Y-m-d',
                ], [
                    'nama_instansi.required' => 'Nama Rumah Sakit/Dinkes tidak boleh kosong',
                    'instansi_pengirim_nama.required' => 'Instansi Pengirim tidak boleh kosong',
                    'tgl_masuk_sampel.date' => 'Tanggal Masuk Sampel tidak valid',
                    'tgl_masuk_sampel.date_format' => 'Format Tanggal Masuk Sampel harus yyyy-mm-dd',
                    'nik.digits' => 'NIK terdiri dari 16 karakter',
                    'nama.required' => 'Nama Pasien Tidak Boleh Kosong',
                    'tgl_lahir.date' => 'Tanggal Lahir tidak valid',
                    'tgl_lahir.date_format' => 'Format Tanggal Lahir harus yyyy-mm-dd',
                    'kode_sampel.required' => 'Nomor Sampel tidak boleh kosong',
                    'jenis_sampel.required' => 'Jenis Sampel tidak boleh kosong',
                    'swab_ke.numeric' => 'Swab ke harus berupa angka',
                    'tanggal_swab.date' => 'Tanggal Swab tidak valid',
                    'tanggal_swab.date_format' => 'Format Tanggal Swab harus yyyy-mm-dd',
                ]);

                $v->after(function ($validator) use ($row) {
                    $user = Auth::user();
                    $nomorsampel = Sampel::where('nomor_sampel', strtoupper($row['kode_sampel']))
                        ->where('lab_satelit_id', $user->lab_satelit_id)->first();
                    if ($nomorsampel != null) {
                        $validator->errors()->add("reg_sampel_nomor", "Nomor Sampel sudah digunakan {$row['kode_sampel']}");
                    }
                })->validate();

                $register = new Register;
                $register->register_uuid = (string) \Illuminate\Support\Str::uuid();
                $register->created_at = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                $register->creator_user_id = $user->id;
                $register->lab_satelit_id = $user->lab_satelit_id;
                $register->instansi_pengirim_nama = $row->get('nama_instansi');
                $register->sumber_pasien = $row->get('kategori');
                $register->instansi_pengirim = $row->get('instansi_pengirim');
                $register->status = strtolower($row->get('status'));
                $register->swab_ke = $row->get('swab_ke');
                if ($row->get('tanggal_swab') != '') {
                    $register->tanggal_swab = date('Y-m-d', strtotime($row->get('tanggal_swab')));
                }
                $register->save();

                $pasien = new Pasien;
                $pasien->nik = $this->__parseNIK($row->get('nik'));
                $pasien->nama_lengkap = $row->get('nama');
                $pasien->jenis_kelamin = $row->get('jenis_kelamin');
                if ($row->get('tgl_lahir') != '') {
                    $pasien->tanggal_lahir = date('Y-m-d', strtotime($row->get('tgl_lahir')));
                }
                $pasien->kota_id = $this->__getKota($row);
                $pasien->kecamatan = $row->get('kecamatan');
                $pasien->kelurahan = $row->get('desakelurahan');
                $pasien->alamat_lengkap = $row->get('alamat');
                $pasien->usia_tahun = $row->get('usia');
                $pasien->lab_satelit_id = $user->lab_satelit_id;
                $pasien->kewarganegaraan = strtoupper($row->get('kewarganegaraan'));
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
                $jenissampel = JenisSampel::where('nama', $row->get('jenis_sampel'))->first();

                $sampel = new Sampel();
                $sampel->nomor_sampel = strtoupper($row->get('kode_sampel'));
                $sampel->sampel_status = 'sample_taken';
                $sampel->lab_satelit_id = $user->lab_satelit_id;
                $sampel->creator_user_id = $user->id;
                $sampel->register_id = $register->id;
                $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
                $sampel->jenis_sampel_id = $jenissampel ? $jenissampel->id : 999999;
                $sampel->jenis_sampel_nama = $row->get('jenis_sampel');
                $sampel->created_at = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                $sampel->waktu_sample_taken = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel') . ' ' . date('H:i:s')));
                $sampel->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    private function __getKota(Collection $row)
    {
        $kotakab = $row->get('kotakab');
        $kota = Kota::where('id', $kotakab)->first();
        if (!$kota) {
            $kotaId = (int)substr(($row->get('nik')), 0, 4);
            $kota = Kota::where('id', $kotaId)->first();
        }

        // abort_if(!$kota, 403, "Kota domisili tidak ditemukan pada pasien dengan NIK {$row->get('nik')}");

        return $kota != null ? $kota->id : null;
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

}
