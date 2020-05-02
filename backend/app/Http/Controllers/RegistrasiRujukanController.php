<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\RiwayatKunjungan;
use App\Models\RiwayatPenyakitPenyerta;
use App\Models\PasienRegister;
use App\Models\Sampel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Validation\Rule;
use Auth;


class RegistrasiRujukanController extends Controller
{
    public function cekData(Request $request)
    {
        $nomor = $request->get('nomor_sampel');
        $sampel = Sampel::where('nomor_sampel',$nomor)->first();
        if(!$sampel) {
            return response()->json([
                'status' => 400,
                'message' => 'Nomor Sampel Tidak Ditemukan',
                'result' => []
            ]);
        }else {
            if($sampel->register_id!=null) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Sampel sudah memiliki data pasien',
                    'result' => []
                ]);
            }else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Sampel dimukan',
                    'result' => $sampel
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $v = Validator::make($request->all(),[
            'reg_kewarganegaraan' => 'required',
            'reg_sumberpasien' => 'required',
            'reg_nama_pasien' => 'required',
            'reg_nik'  => 'required|max:16',
            'reg_tempatlahir' => 'required',
            'reg_tgllahir' => 'required',
            'reg_nohp' => 'required|max:15',
            'reg_kota' => 'required',
            'reg_kecamatan' => 'required',
            'reg_kelurahan' => 'required',
            'reg_alamat' => 'required',
            'reg_rt' => 'required',
            'reg_rw' => 'required',
            'reg_suhu' => 'required',
            'reg_jk'=>'required',
        ], [
            'reg_kewarganegaraan.required' => 'Mohon pilih kewarganegaraan',
            'reg_sumberpasien' => 'Mohon pilih sumber kedatangan pasien',
            'peg_nama_pasien.required' => 'Nama Pasien tidak boleh kosong',
            'reg_nik.max' => 'NIK maksimal terdiri dari :max karakter',
            'reg_nik.required' => 'NIK Pasien tidak boleh kosong',
            'reg_tempatlahir.required' => 'Tempat lahir tidak boleh kosong',
            'reg_tgllahir' => 'Tanggal lahir tidak boleh kosong',
            'reg_nohp' => 'No HP tidak boleh kosong',
            'reg_kota' => 'Mohon pilih salah satu kota/kabupaten',
            'reg_kecamatan' => 'Kecamatan tidak boleh ksoong',
            'reg_kelurahan' => 'Kelurahan tidak boleh ksoong',
            'reg_alamat' => 'Alamat tidak boleh kosong',
            'reg_rt' => 'RT tidak boleh kosong',
            'reg_rw' => 'RW tidak boleh kosong',
            'reg_suhu' => 'Suhu tidak boleh kosong',
        ]);
        $v->validate();
        $nomor_register = $request->input('reg_no');
        if (Register::where('nomor_register', $nomor_register)->exists()) {
            $nomor_register = with(new \App\Http\Controllers\V1\RegisterController)->generateNomorRegister(null, 'rujukan');
        }
        $register = Register::create([
            'nomor_register'=> $nomor_register,
            'fasyankes_id'=> null,
            'nomor_rekam_medis'=> null,
            'nama_dokter'=> null,
            'no_telp'=> null,
            'register_uuid' => (string) Str::uuid(),
            'creator_user_id' => $user->id,
            'sumber_pasien' => $request->get('reg_sumberpasien'),
            'jenis_registrasi' => 'rujukan'
        ]);

        $pasien = Pasien::where('nik',$request->get('reg_nik'))->first();
        if(!$pasien) {
            $pasien = new Pasien;
        }
        $pasien->nama_lengkap = $request->get('reg_nama_pasien');
        $pasien->kewarganegaraan = $request->get('reg_kewarganegaraan');
        $pasien->nik = $request->get('reg_nik');
        $pasien->tempat_lahir = $request->get('reg_tempatlahir');
        $pasien->tanggal_lahir = $request->get('reg_tgllahir');
        $pasien->no_hp = $request->get('reg_nohp');
        $pasien->kota_id = $request->get('reg_kota');
        $pasien->kecamatan = $request->get('reg_kecamatan');
        $pasien->kelurahan = $request->get('reg_kelurahan');
        $pasien->alamat_lengkap = $request->get('reg_alamat');
        $pasien->no_rt = $request->get('reg_rt');
        $pasien->no_rw = $request->get('reg_rw');
        $pasien->suhu = parseDecimal($request->get('reg_suhu'));
        $pasien->jenis_kelamin = $request->get('reg_jk');
        $pasien->keterangan_lain = $request->get('reg_keterangan');
        $pasien->save();

        // $tandaGejala = $this->getRequestTandaGejala($request);

        $regis = PasienRegister::create([
            'pasien_id' => $pasien->id,
            'register_id' => $register->id,
        ]);
        $sampel = Sampel::where('nomor_sampel',$request->get('nomor_sampel'))->first();
        if($sampel) {
            $sampel->register_id = $register->id;
            $sampel->nomor_register = $request->input('reg_no');
            $sampel->save();
        }
        return response()->json(['status'=>201,'message'=>'Proses Registrasi Rujukan Berhasil Ditambahkan','result'=>[]]);
    }
}
