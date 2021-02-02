<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterSampel;
use App\Http\Requests\UpdateRegisterSampel;
use App\Models\RegisterLog;
use App\Models\JenisSampel;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\Sampel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistersampelController extends Controller
{
    public function storesampel(StoreRegisterSampel $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            $register = new Register;
            $register->nomor_register = generateNomorRegister();
            $register->register_uuid = (string)Str::uuid();
            $register->creator_user_id = $user->id;
            $register->lab_satelit_id = $user->lab_satelit_id;
            $register->fasyankes_id = $request->get('reg_fasyankes_id');
            $register->fasyankes_pengirim = $request->get('reg_fasyankes_pengirim');
            $register->nama_rs = $request->get('reg_nama_rs');
            $register->instansi_pengirim_nama = $request->get('reg_nama_rs');
            $register->instansi_pengirim = $request->get('reg_fasyankes_pengirim');
            $register->sumber_pasien = $request->get('reg_sumber_pasien');
            $register->status = $request->get('reg_status');
            $register->swab_ke = $request->get('reg_swab_ke');
            if ($request->get('reg_tanggal_swab') != '') {
                $register->tanggal_swab = date('Y-m-d', strtotime($request->get('reg_tanggal_swab')));
            }
            $register->save();

            $pasien = new Pasien;
            $pasien->nama_lengkap = $request->get('reg_nama_pasien');
            $pasien->kewarganegaraan = $request->get('reg_kewarganegaraan');
            $pasien->keterangan_warganegara = $request->get('reg_keterangan_warganegara');
            $pasien->nik = $request->get('reg_nik');
            $pasien->tempat_lahir = $request->get('reg_tempatlahir');
            if ($request->get('reg_tgllahir') != null) {
                $pasien->tanggal_lahir = date('Y-m-d', strtotime($request->get('reg_tgllahir')));
            }
            $pasien->no_hp = $request->get('reg_nohp');
            $pasien->kode_provinsi = $request->get('reg_kode_provinsi');
            $pasien->nama_provinsi = $request->get('reg_nama_provinsi');
            $pasien->kota_id = $request->get('reg_kode_kota');
            $pasien->kecamatan = $request->get('reg_nama_kecamatan');
            $pasien->kelurahan = $request->get('reg_nama_kelurahan');
            $pasien->kode_kabupaten = $request->get('reg_kode_kota');
            $pasien->nama_kabupaten = $request->get('reg_nama_kota');
            $pasien->kode_kecamatan = $request->get('reg_kode_kecamatan');
            $pasien->nama_kecamatan = $request->get('reg_nama_kecamatan');
            $pasien->kode_kelurahan = $request->get('reg_kode_kelurahan');
            $pasien->nama_kelurahan = $request->get('reg_nama_kelurahan');
            $pasien->alamat_lengkap = $request->get('reg_alamat');
            $pasien->sumber_pasien = $request->get('reg_sumber_pasien');
            $pasien->no_rt = $request->get('reg_rt');
            $pasien->no_rw = $request->get('reg_rw');
            $pasien->jenis_kelamin = $request->get('reg_jk');
            $pasien->keterangan_lain = $request->get('reg_keterangan');
            $pasien->usia_tahun = $request->get('reg_usia_tahun');
            $pasien->usia_bulan = $request->get('reg_usia_bulan');
            $pasien->pelaporan_id = $request->get('reg_pelaporan_id');
            $pasien->pelaporan_id_case = $request->get('reg_pelaporan_id_case');
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
                'catatan' => $request->get('reg_keterangan'),
            ]);

            $sampel = new Sampel();
            $sampel->nomor_sampel = strtoupper($request->reg_sampel_nomor);
            $sampel->jenis_sampel_id = $request->reg_sampel_jenis_sampel;
            if ($request->reg_sampel_jenis_sampel != 999999) {
                $jenis_sampel = JenisSampel::where('id', $request->reg_sampel_jenis_sampel)->first();
                $sampel->jenis_sampel_nama = optional($jenis_sampel)->nama;
            } else {
                $sampel->jenis_sampel_nama = $request->reg_sampel_namadiluarjenis;
            }
            $sampel->register_id = $register->id;
            $sampel->lab_satelit_id = $user->lab_satelit_id;
            $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
            $sampel->creator_user_id = $user->id;
            $sampel->sampel_status = 'sample_taken';
            $sampel->waktu_sample_taken = date('Y-m-d H:i:s');
            $sampel->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json(['status' => 201, 'message' => 'Proses Registrasi Sampel Berhasil Ditambahkan', 'result' => []]);
    }

    public function storeUpdate(UpdateRegisterSampel $request, $regis_id, $pasien_id)
    {
        DB::beginTransaction();

        try {
            $register = Register::where('id', $regis_id)->first();
            $pasien = Pasien::where('id', $pasien_id)->first();
            $sampel = Sampel::where('register_id', $regis_id)->first();
            $pengambilan_sampel = PengambilanSampel::where('id', $sampel->pengambilan_sampel_id)->first();

            $registerOrigin = clone $register;
            $pasienOrigin = clone $pasien;
            $sampelOrigin = clone $sampel;

            $user = Auth::user();

            $register->creator_user_id = $user->id;
            $register->lab_satelit_id = $user->lab_satelit_id;
            $register->fasyankes_id = $request->get('reg_fasyankes_id');
            $register->fasyankes_pengirim = $request->get('reg_fasyankes_pengirim');
            $register->nama_rs = $request->get('reg_nama_rs');
            $register->instansi_pengirim_nama = $request->get('reg_nama_rs');
            $register->instansi_pengirim = $request->get('reg_fasyankes_pengirim');
            $register->sumber_pasien = $request->get('reg_sumber_pasien');
            $register->status = $request->get('reg_status');
            $register->swab_ke = $request->get('reg_swab_ke');
            if ($request->get('reg_tanggal_swab') != '') {
                $register->tanggal_swab = date('Y-m-d', strtotime($request->get('reg_tanggal_swab')));
            }
            if ($register->nomor_register == null) {
                $register->nomor_register = generateNomorRegister();
            }
            $register->save();

            $pasien->nama_lengkap = $request->get('reg_nama_pasien');
            $pasien->kewarganegaraan = $request->get('reg_kewarganegaraan');
            $pasien->keterangan_warganegara = $request->get('reg_keterangan_warganegara');
            $pasien->nik = $request->get('reg_nik');
            $pasien->tempat_lahir = $request->get('reg_tempatlahir');
            if ($request->get('reg_tgllahir') != null) {
                $pasien->tanggal_lahir = date('Y-m-d', strtotime($request->get('reg_tgllahir')));
            }
            $pasien->no_hp = $request->get('reg_nohp');
            $pasien->kode_provinsi = $request->get('reg_kode_provinsi');
            $pasien->nama_provinsi = $request->get('reg_nama_provinsi');
            $pasien->kota_id = $request->get('reg_kode_kota');
            $pasien->kecamatan = $request->get('reg_nama_kecamatan');
            $pasien->kelurahan = $request->get('reg_nama_kelurahan');
            $pasien->kode_kabupaten = $request->get('reg_kode_kota');
            $pasien->nama_kabupaten = $request->get('reg_nama_kota');
            $pasien->kode_kecamatan = $request->get('reg_kode_kecamatan');
            $pasien->nama_kecamatan = $request->get('reg_nama_kecamatan');
            $pasien->kode_kelurahan = $request->get('reg_kode_kelurahan');
            $pasien->nama_kelurahan = $request->get('reg_nama_kelurahan');
            $pasien->alamat_lengkap = $request->get('reg_alamat');
            $pasien->sumber_pasien = $request->get('reg_sumber_pasien');
            $pasien->no_rt = $request->get('reg_rt');
            $pasien->no_rw = $request->get('reg_rw');
            $pasien->jenis_kelamin = $request->get('reg_jk');
            $pasien->keterangan_lain = $request->get('reg_keterangan');
            $pasien->usia_tahun = $request->get('reg_usia_tahun');
            $pasien->usia_bulan = $request->get('reg_usia_bulan');
            $pasien->pelaporan_id = $request->get('reg_pelaporan_id');
            $pasien->pelaporan_id_case = $request->get('reg_pelaporan_id_case');
            $pasien->lab_satelit_id = $user->lab_satelit_id;
            $pasien->save();


            $pengambilan_sampel->catatan = $request->get('reg_keterangan');
            $pengambilan_sampel->save();

            $sampel->nomor_sampel = strtoupper($request->reg_sampel_nomor);
            $sampel->jenis_sampel_id = $request->reg_sampel_jenis_sampel;
            if ($request->reg_sampel_jenis_sampel != 999999) {
                $jenis_sampel = JenisSampel::where('id', $request->reg_sampel_jenis_sampel)->first();
                $sampel->jenis_sampel_nama = optional($jenis_sampel)->nama;
            } else {
                $sampel->jenis_sampel_nama = $request->reg_sampel_namadiluarjenis;
            }
            $sampel->register_id = $register->id;
            $sampel->lab_satelit_id = $user->lab_satelit_id;
            $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
            $sampel->creator_user_id = $user->id;
            $sampel->save();

            $registerChanges = $register->getChanges();
            $pasienChanges = $pasien->getChanges();
            $sampelChanges = $sampel->getChanges();

            $registerLogs = array();
            foreach ($registerChanges as $key => $value) {
                if ($key != "updated_at") {
                    $registerLogs[$key]["from"] = $key == 'status' && $registerOrigin[$key] ? STATUSES[$registerOrigin[$key]] : $registerOrigin[$key];
                    $registerLogs[$key]["to"] = $key == 'status' ? STATUSES[$value] : $value;
                }
            }

            $pasienLogs = array();
            foreach ($pasienChanges as $key => $value) {
                if ($key != "updated_at") {
                    $pasienLogs[$key]["from"] = $key == 'tanggal_lahir' ? date('d-m-Y', strtotime($pasienOrigin[$key])) : $pasienOrigin[$key];
                    $pasienLogs[$key]["to"] = $key == 'tanggal_lahir' ? date('d-m-Y', strtotime($value)) : $value;
                }
            }

            $sampelLogs = array();
            foreach ($sampelChanges as $key => $value) {
                if ($key != "updated_at") {
                    $sampelLogs[$key]["from"] = $sampelOrigin[$key];
                    $sampelLogs[$key]["to"] = $value;
                }
            }

            $register->logs()->create([
                "user_id" => $user->id,
                "info" => json_encode(array(
                    "register" => $registerLogs,
                    "sampel" => $sampelLogs,
                    "pasien" => $pasienLogs
                ))
            ]);


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json(['status' => 200, 'message' => 'Data Register Berhasil Diubah']);
    }

    public function getById(Request $request, $register_id, $pasien_id)
    {
        $register = Register::where('id', $register_id)->first();
        $pasien = Pasien::where('pasien.id', $pasien_id)->first();
        $sampel = Sampel::where('register_id', $register_id)->first();
        $pengambilan_sampel = PengambilanSampel::where('id', $sampel->pengambilan_sampel_id)->first();
        return response()->json([
            'reg_fasyankes_id' => $register->fasyankes_id,
            'reg_fasyankes_pengirim' => $register->fasyankes_pengirim,
            'reg_nama_rs' => $register->nama_rs,
            'reg_kewarganegaraan' => $pasien->kewarganegaraan,
            'reg_nama_pasien' => $pasien->nama_lengkap,
            'reg_nik' => $pasien->nik,
            'reg_tempatlahir' => $pasien->tempat_lahir,
            'reg_tgllahir' => parseDate($pasien->tanggal_lahir),
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
            'reg_status' => $register->status,
            'reg_swab_ke' => $register->swab_ke,
            'reg_tanggal_swab' => parseDate($register->tanggal_swab),
            'reg_sumber_pasien' => $register->sumber_pasien,
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
            'reg_keterangan' => optional($pengambilan_sampel)->catatan,
            'reg_register' => $register->lab_satelit_id,
            'reg_pasien' => $pasien->lab_satelit_id,
            'reg_sampel_id' => $sampel->id,
            'reg_keterangan_warganegara' => $pasien->keterangan_warganegara,
            'reg_sampel_status' => $sampel->sampel_status,
            'reg_register_perujuk_id' => $sampel->register_perujuk_id,
        ]);
    }

    public function logs($register_id)
    {
        $model = RegisterLog::where('register_id', $register_id)->join('users', 'users.id', 'register_logs.user_id')
            ->whereRaw("(info->>'pasien' <> '[]'::text or info->>'register' <> '[]'::text or info->>'sampel' <> '[]'::text)")
            ->select('info', 'register_logs.created_at', 'users.name as updated_by')->get();

        foreach ($model as $key => $val) {
            $info = json_decode($val->info);

            if (isset($info->pasien->status)) {
                $info->pasien->status->from = STATUSES[$info->pasien->status->from] ?? null;
                $info->pasien->status->to = STATUSES[$info->pasien->status->to] ?? null;
            }

            $val->info = json_encode($info);
        }

        return response()->json([
            'result' => $model,
            'statys' => 'success'
        ], 200);
    }

    public function delete(Register $register)
    {
        if ($register->sampel) {
            $register->sampel()->delete();
        }
        $register->delete();
        return response()->json(['message' => 'Berhasil menghapus data sampel']);
    }
}
