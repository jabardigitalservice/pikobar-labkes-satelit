<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\Pasien;
use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class RegisterController extends Controller
{
    public function generateNomorRegister()
    {
        return response()->json(Uuid::uuid1()->toString());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegisterRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {

            $register = Register::create([
                'nomor_register'=> $request->input('nomor_register'),
                'fasyankes_id'=> $request->input('fasyankes_id'),
                'nomor_rekam_medis'=> $request->input('nomor_rekam_medis'),
                'nama_dokter'=> $request->input('nama_dokter'),
                'no_telp'=> $request->input('no_telp'),
            ]);

            $pasien = Pasien::create([
                "nama_depan"=> $request->input('nama_depan'),
                "nama_belakang"=> $request->input('nama_belakang'),
                "kewarganegaraan"=> $request->input('kewarganegaraan'),
                "no_ktp"=> $request->input('no_ktp'),
                "no_sim"=> $request->input('no_sim'),
                "no_kk"=> $request->input('no_kk'),
                "tanggal_lahir"=> $request->input('tanggal_lahir'),
                "tempat_lahir"=> $request->input('tempat_lahir'),
                "no_hp"=> $request->input('no_hp_pasien'),
                "no_telp"=> $request->input('no_telp_pasien'),
                "pekerjaan"=> $request->input('pekerjaan'),
                "jenis_kelamin"=> $request->input('jenis_kelamin'),
                "kota_id"=> $request->input('kota_id'),
                "kecamatan"=> $request->input('kecamatan'),
                "kelurahan"=> $request->input('kelurahan'),
                "no_rw"=> $request->input('no_rw'),
                "no_rt"=> $request->input('no_rt'),
                "alamat_lengkap"=> $request->input('alamat_lengkap'),
                "keterangan_lain"=> $request->input('keterangan_lain')
            ]);

            $register->pasiens()->attach($pasien);
            
            
            DB::commit();

            return response()->json($register);


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
