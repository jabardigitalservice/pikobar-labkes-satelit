<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\RiwayatKunjungan;
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

            // Check existing pasien
            $pasienWithKTP = Pasien::whereNoKtp($request->input('no_ktp'))->first();
            $pasienWithSIM = Pasien::whereNoSim($request->input('no_sim'))->first();

            if (!$request->input('force_update_pasien') && ($pasienWithKTP || $pasienWithSIM)) {
                DB::rollBack();

                return response()->json(
                    __("Gagal menambah data. Pasien dengan nomor identitas tersebut telah tersedia."), 
                    422
                );
            }

            $dataPasien = $this->getRequestPasien($request);

            $pasien = Pasien::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($dataPasien, ['no_ktp']),
                $dataPasien
            );

            $riwayatKunjungan = new RiwayatKunjungan([
                'riwayat'=> $request->input('riwayat_kunjungan')
            ]);

            $tandaGejala = [
                "pasien_rdt"=> $request->input('tanda_gejala.pasien_rdt'),
                "hasil_rdt_positif"=> $request->input('tanda_gejala.hasil_rdt_positif'),
                "tanggal_onset_gejala"=> $request->input('tanda_gejala.tanggal_onset_gejala'),
                "tanggal_rdt"=> $request->input('tanda_gejala.tanggal_rdt'),
                "keterangan_rdt"=> $request->input('tanda_gejala.keterangan_rdt'),
                "daftar_gejala"=> $request->input('tanda_gejala.daftar_gejala'),
                "gejala_lain"=> $request->input('tanda_gejala.gejala_lain'),   
            ];

            $register->pasiens()->attach($pasien);
            $register->riwayatKunjungan()->save($riwayatKunjungan);
            $register->gejalaPasien()->attach($pasien, $tandaGejala);
            
            DB::commit();

            $response = $register->toArray() + [
                'pasien'=> $pasien,
                'fasyankes'=> $register->fasyankes,
                'riwayat_kunjungan'=> $register->riwayatKunjungan->getAttribute('riwayat'),
                'tanda_gejala'=> $register->gejalaPasien()->first()->pivot,
            ];

            return response()->json($response);


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    private function getRequestPasien(Request $request) : array
    {
        return [
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
        ];
    }
}
