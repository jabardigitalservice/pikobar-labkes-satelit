<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Requests\UpdateRegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\RiwayatKunjungan;
use App\Models\RiwayatPenyakitPenyerta;
use Illuminate\Http\Request;
use App\Models\Sampel;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use App\Rules\UniqueSampel;
use App\Models\PasienRegister;
use App\Exports\RegisMandiriExport;
use App\Models\JenisSampel;
use App\Models\PengambilanSampel;

class RegistersampelController extends Controller
{
    public function requestNomor(Request $request)
    {
        $jenis = $request->get('tipe');
        return response()->json(['status'=>200,
        'message'=>'success',
        'result'=>$this->generateNomorRegister(null,$jenis)]);
    }
    public function generateNomorRegister($date=null, $jenis_registrasi = null)
    {
        if(!$date) {
            $date = date('Ymd');
        }
        if (empty($jenis_registrasi)) {
            $kode_registrasi = 'S';
        } else if ($jenis_registrasi == 'mandiri') {
            $kode_registrasi = 'L';
        } else if ($jenis_registrasi == 'rujukan') {
            $kode_registrasi = 'R';
        }
        $res = DB::select("select max(right(nomor_register, 4))::int8 val from register where nomor_register ilike '{$kode_registrasi}{$date}%'");
        if (count($res)) {
            $nextnum = $res[0]->val + 1;
        } else {
            $nextnum = 1;
        }
        return $kode_registrasi . $date . str_pad($nextnum,4,"0",STR_PAD_LEFT);
    }

    public function storesampel(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $v = Validator::make($request->all(),[
                'reg_instansi_pengirim' => 'required',
                'reg_instansi_pengirim_nama' => 'required',
                'reg_nama_pasien' => 'required',
                'reg_nohp' => 'nullable|numeric',
                // 'reg_kota' => 'required',
                'reg_nik'  => 'nullable|numeric|max:16',
                'reg_sampel.*' => [
                    'required',
                    new UniqueSampel(),
                ],
            ], [
                'reg_instansi_pengirim.required' => 'Instansi Pengirim tidak boleh kosong',
                'reg_nohp.numeric' => 'No. Telp / HP hanya berupa angka',
                'reg_instansi_pengirim_nama.required' => 'Nama Rumah Sakit/Dinkes tidak boleh kosong',
                // 'reg_kota.required' => 'Kota tidak boleh kosong',
                
                'reg_nama_pasien.required' => 'Nama Pasien tidak boleh kosong',
                'reg_nik.max' => 'NIK terdiri dari :max karakter',
                'reg_nik.numeric' => 'NIK terdiri hanya berupa angka',
                'reg_sampel.required' => 'Mohon isi minimal satu nomor sampel',
            ]);
            $v->validate();
            // $nomor_register = $request->input('reg_no');
            // if (Register::where('nomor_register', $nomor_register)->exists()) {
            //     $nomor_register = $this->generateNomorRegister();
            // }

            $register = new Register;
            $register->register_uuid = (string) Str::uuid();
            $register->creator_user_id = $user->id;
            $register->lab_satelit_id = $user->lab_satelit_id;
            $register->instansi_pengirim = $request->get('reg_instansi_pengirim');
            $register->instansi_pengirim_nama = $request->get('reg_instansi_pengirim_nama');
            $register->save();

            // $pasien = Pasien::where('nik',$request->get('reg_nik'))->first();
            // if(!$pasien) {
                // }
            $pasien = new Pasien;
            $pasien->nama_lengkap = $request->get('reg_nama_pasien');
            $pasien->kewarganegaraan = $request->get('reg_kewarganegaraan');
            $pasien->nik = $request->get('reg_nik');
            $pasien->tempat_lahir = $request->get('reg_tempatlahir');
            if ($request->get('reg_tgllahir') != null) {
                $pasien->tanggal_lahir = date('Y-m-d',strtotime($request->get('reg_tgllahir')));
            }
            $pasien->no_hp = $request->get('reg_nohp');
            $pasien->kota_id = $request->get('reg_kota');
            $pasien->kecamatan = $request->get('reg_kecamatan');
            $pasien->kelurahan = $request->get('reg_kelurahan');
            $pasien->alamat_lengkap = $request->get('reg_alamat');
            $pasien->no_rt = $request->get('reg_rt');
            $pasien->no_rw = $request->get('reg_rw');
            $pasien->jenis_kelamin = $request->get('reg_jk');
            $pasien->keterangan_lain = $request->get('reg_keterangan');
            $pasien->usia_tahun = $request->get('reg_usia_tahun');
            $pasien->usia_bulan = $request->get('reg_usia_bulan');
            $pasien->lab_satelit_id = $user->lab_satelit_id;
            $pasien->save();

            // $tandaGejala = $this->getRequestTandaGejala($request);

            $regis = PasienRegister::create([
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

            if($request->get('reg_sampel')) {
                foreach($request->get('reg_sampel') as $rows) {
                    if ($rows['nomor'] != "") {
                        $sampel = new Sampel;
                        $sampel->nomor_sampel = $rows['nomor'];
                        $sampel->jenis_sampel_id = $rows['sam_jenis_sampel'];
                        if($rows['sam_jenis_sampel']!=999999){
                            $jenis_sampel = JenisSampel::where('id',$rows['sam_jenis_sampel'])->first();
                            $sampel->jenis_sampel_nama = $jenis_sampel->nama;
                        }else {
                            $sampel->jenis_sampel_nama = $rows['sam_namadiluarjenis'];
                        }
                        
                        $sampel->register_id = $register->id;
                        $sampel->lab_satelit_id = $user->lab_satelit_id;
                        $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
                        $sampel->sampel_status = 'sample_taken';
                        $sampel->waktu_sample_taken =  date('Y-m-d H:i:s');
                        // $sampel->updateState('sample_taken');
                        $sampel->save();
                    }
                }
            }
         DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }     

        return response()->json(['status'=>201,'message'=>'Proses Registrasi Sampel Berhasil Ditambahkan','result'=>[]]);
    }

    public function storeUpdate(Request $request,$regis_id, $pasien_id)
    {
        DB::beginTransaction();

        try {
            $register = Register::where('id',$regis_id)->first();
            $pasien   = Pasien::where('id',$pasien_id)->first();
            $user = Auth::user();
            $v = Validator::make($request->all(),[
                'reg_instansi_pengirim' => 'required',
                'reg_instansi_pengirim_nama' => 'required',
                    'reg_nama_pasien' => 'required',
                    'reg_nik'  => 'nullable|max:16',
                    'reg_nohp' => 'nullable|numeric',
                    // 'reg_sampel.*' => [
                    //     'required',
                    //     new UniqueSampel(),
                    // ],
            ], [
                    'reg_instansi_pengirim.required' => 'Instansi Pengirim tidak boleh kosong',
                    'reg_instansi_nama.required' => 'Nama Rumah Sakit/Dinkes tidak boleh kosong',
                    'reg_nama_pasien.required' => 'Nama Pasien tidak boleh kosong',
                    'reg_nik.max' => 'NIK terdiri dari :max karakter',
                    'reg_nohp.numeric' => 'No. Telp / HP hanya berupa angka',
            ]);
            $v->validate();
            $register->register_uuid = (string) Str::uuid();
            $register->creator_user_id = $user->id;
            $register->instansi_pengirim = $request->get('reg_instansi_pengirim');
            $register->instansi_pengirim_nama = $request->get('reg_instansi_pengirim_nama');
            $register->save();
            
            
            $pasien->nama_lengkap = $request->get('reg_nama_pasien');
            $pasien->kewarganegaraan = $request->get('reg_kewarganegaraan');
            $pasien->nik = $request->get('reg_nik');
            $pasien->tempat_lahir = $request->get('reg_tempatlahir');
            if ($request->get('reg_tgllahir') != null) {
                $pasien->tanggal_lahir = date('Y-m-d',strtotime($request->get('reg_tgllahir')));
            }
            $pasien->no_hp = $request->get('reg_nohp');
            $pasien->kota_id = $request->get('reg_kota');
            $pasien->kecamatan = $request->get('reg_kecamatan');
            $pasien->kelurahan = $request->get('reg_kelurahan');
            $pasien->alamat_lengkap = $request->get('reg_alamat');
            $pasien->no_rt = $request->get('reg_rt');
            $pasien->no_rw = $request->get('reg_rw');
            $pasien->jenis_kelamin = $request->get('reg_jk');
            $pasien->keterangan_lain = $request->get('reg_keterangan');
            $pasien->usia_tahun = $request->get('reg_usia_tahun');
            $pasien->usia_bulan = $request->get('reg_usia_bulan');
            $pasien->save();

            // $tandaGejala = $this->getRequestTandaGejala($request);

            // $regis = PasienRegister::create([
            //     'pasien_id' => $pasien->id,
            //     'register_id' => $register->id,
            // ]);
            if($request->get('reg_sampel')) {
                    foreach($request->get('reg_sampel') as $rows) {
                        if ($rows['nomor'] != "") {
                            $sampel = Sampel::where('nomor_sampel',$rows['nomor'])->first();
                            if ($sampel == null) {
                                $sampel = new Sampel;
                                $sampel->sampel_status = 'sample_taken';
                                $sampel->waktu_sample_taken =  date('Y-m-d H:i:s');
                            }
                            $sampel->nomor_sampel = $rows['nomor'];
                            $sampel->jenis_sampel_id = $rows['sam_jenis_sampel'];
                            if($rows['sam_jenis_sampel']!=999999){
                                $jenis_sampel = JenisSampel::where('id',$rows['sam_jenis_sampel'])->first();
                                $sampel->jenis_sampel_nama = $jenis_sampel->nama;
                            }else {
                                $sampel->jenis_sampel_nama = $rows['sam_namadiluarjenis'];
                            }
                            $sampel->lab_satelit_id = $user->lab_satelit_id;
                            $sampel->register_id = $register->id;
                            $sampel->save();
                        }
                    }
                }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }  

        return response()->json(['status'=>200,'message'=>'Data Register Berhasil Diubah']);
    }

    public function deleteSample($id)
    {
        $sm = Sampel::where('id',$id)->first()->delete();
        return response()->json(['status'=>200,'message'=>'Berhasil menghapus data sample']);
    }

    public function getById(Request $request, $register_id, $pasien_id)
    {
        $register = Register::where('id',$register_id)->first();
        $pasien   = Pasien::where('pasien.id', $pasien_id)->leftJoin('kota','kota.id','pasien.kota_id')->first();
        $sampel   = Sampel::where('register_id',$register_id)->get();
        $smp = [];
        foreach($sampel as $sm) {
            array_push($smp, array(
                'nomor'=> $sm->nomor_sampel,
                'sam_jenis_sampel' => (int) $sm->jenis_sampel_id,
                'sam_namadiluarjenis' => $sm->jenis_sampel_nama,
                'id' => $sm->id,
            ));
            // $smp['nomor'] = $sm->nomor_sampel;
            // $smp['id'] = $sm->id;
        }
        return response()->json([
            'instansi_pengirim' =>  $register->instansi_pengirim,
            'instansi_pengirim_nama' =>  $register->instansi_pengirim_nama,
            'reg_kewarganegaraan' =>  $pasien->kewarganegaraan,
            'reg_nama_pasien' =>  $pasien->nama_lengkap,
            'reg_nik' =>  $pasien->nik,
            'reg_tempatlahir' =>  $pasien->tempat_lahir,
            'reg_tgllahir' =>  $pasien->tanggal_lahir,
            'reg_nohp' =>  $pasien->no_hp,
            'reg_kota' =>  $pasien->kota_id,
            'reg_kecamatan' =>  $pasien->kecamatan,
            'reg_kelurahan' =>  $pasien->kelurahan,
            'reg_alamat' =>  $pasien->alamat_lengkap,
            'reg_rt' =>  $pasien->no_rt,
            'reg_rw' =>   $pasien->no_rw,
            'reg_sampel' =>  $smp,
            'reg_keterangan' =>  $pasien->keterangan_lain,
            'reg_jk' => $pasien->jenis_kelamin,
            'nama_kota'=>$pasien->nama,
            'reg_usia_tahun' => $pasien->usia_tahun,
            'reg_usia_bulan' => $pasien->usia_bulan,
         ]);
    }

    public function exportExcelMandiri(Request $request)
    {
        $request->validate([
            'start_date'=> 'nullable', // 'date|date_format:Y-m-d',
            'end_date'=> 'nullable', // 'date|date_format:Y-m-d',
        ]);

        $payload = [];

        if ($request->has('start_date')) {
            $payload['startDate'] = parseDate($request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $payload['endDate'] = parseDate($request->input('end_date'));
        }

        return (new SampelVerifiedExport($payload))->download('registrasi-mandiri-'.time().'.xlsx');
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
                'nomor_register'=> $this->generateNomorRegister(date('Ymd'),'mandiri'),
                'fasyankes_id'=> $request->input('fasyankes_id'),
                'nomor_rekam_medis'=> $request->input('nomor_rekam_medis'),
                'nama_dokter'=> $request->input('nama_dokter'),
                'no_telp'=> $request->input('no_telp'),
                'register_uuid' => (string) Str::uuid(),
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

            $tandaGejala = $this->getRequestTandaGejala($request);

            $pemeriksaanPenunjang = $this->getRequestPemeriksaanPenunjang($request);

            $riwayatKontak = $this->getRequestRiwayatKontak($request);

            $riwayatLawatan = $this->getRequestRiwayatLawatan($request);

            $penyakitPenyerta = new RiwayatPenyakitPenyerta(
                $this->getRequestPenyakitPenyerta($request)
            );

            $register->pasiens()->attach($pasien);
            $register->riwayatKunjungan()->save($riwayatKunjungan);
            $register->gejalaPasien()->attach($pasien, $tandaGejala);
            $register->pemeriksaanPenunjang()->attach($pasien, $pemeriksaanPenunjang);
            $register->riwayatPenyakitPenyerta()->save($penyakitPenyerta);

            foreach ($riwayatKontak as $key => $riwayat) {
                $register->riwayatKontak()->attach($pasien, $riwayat);
            }

            foreach ($riwayatLawatan as $key => $riwayat) {
                $register->riwayatLawatan()->attach($pasien, $riwayat);
            }
            
            DB::commit();

            return new RegisterResource($register);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    /**
     * Update resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegisterRequest  $request
     * @param \App\Models\Register $register
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegisterRequest $request, Register $register)
    {
        $request->validated();

        DB::beginTransaction();
        try {

            
            $register->gejalaPasien()->detach();
            
            $register->pemeriksaanPenunjang()->detach();
            
            $register->riwayatLawatan()->detach();
            
            $register->riwayatKontak()->detach();
            
            $register->riwayatKunjungan()->delete();

            $register->riwayatPenyakitPenyerta()->delete();

            $updatedRegister = $register->updateOrCreate([
                // 'nomor_register'=> $request->input('nomor_register'),
                'fasyankes_id'=> $request->input('fasyankes_id'),
                'nomor_rekam_medis'=> $request->input('nomor_rekam_medis'),
                'nama_dokter'=> $request->input('nama_dokter'),
                'no_telp'=> $request->input('no_telp'),
            ]);

            $pasien = Pasien::find($request->input('pasien_id'));

            if ($pasien) {
                $dataPasien = $this->getRequestPasien($request);
                $pasien->update($dataPasien);
            }

            $riwayatKunjungan = new RiwayatKunjungan([
                'riwayat'=> $request->input('riwayat_kunjungan')
            ]);

            $tandaGejala = $this->getRequestTandaGejala($request);

            $pemeriksaanPenunjang = $this->getRequestPemeriksaanPenunjang($request);

            $riwayatKontak = $this->getRequestRiwayatKontak($request);

            $riwayatLawatan = $this->getRequestRiwayatLawatan($request);

            $penyakitPenyerta = new RiwayatPenyakitPenyerta(
                $this->getRequestPenyakitPenyerta($request)
            );

            $register->pasiens()->attach($pasien);
            $register->riwayatKunjungan()->save($riwayatKunjungan);
            $register->gejalaPasien()->attach($pasien, $tandaGejala);
            $register->pemeriksaanPenunjang()->attach($pasien, $pemeriksaanPenunjang);
            $register->riwayatPenyakitPenyerta()->save($penyakitPenyerta);

            foreach ($riwayatKontak as $key => $riwayat) {
                $register->riwayatKontak()->attach($pasien, $riwayat);
            }

            foreach ($riwayatLawatan as $key => $riwayat) {
                $register->riwayatLawatan()->attach($pasien, $riwayat);
            }
            
            DB::commit();

            return new RegisterResource($updatedRegister);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }        
    }

    /**
     * Show detail single resource.
     *
     * @param \App\Models\Register $register
     * @return \Illuminate\Http\Response
     */
    public function show(Register $register)
    {
        return new RegisterResource($register);
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

    private function getRequestTandaGejala(Request $request) : array
    {
        return [
            "pasien_rdt"=> $request->input('tanda_gejala.pasien_rdt'),
            "hasil_rdt_positif"=> $request->input('tanda_gejala.hasil_rdt_positif'),
            "tanggal_onset_gejala"=> $request->input('tanda_gejala.tanggal_onset_gejala'),
            "tanggal_rdt"=> $request->input('tanda_gejala.tanggal_rdt'),
            "keterangan_rdt"=> $request->input('tanda_gejala.keterangan_rdt'),
            "daftar_gejala"=> $request->input('tanda_gejala.daftar_gejala'),
            "gejala_lain"=> $request->input('tanda_gejala.gejala_lain'),   
        ];
    }

    private function getRequestPemeriksaanPenunjang(Request $request) : array
    {
        return [
            "xray_paru"=> $request->input('pemeriksaan_penunjang.xray_paru'),
            "penjelasan_xray"=> $request->input('pemeriksaan_penunjang.penjelasan_xray'),
            "leukosit"=> $request->input('pemeriksaan_penunjang.leukosit'),
            "limfosit"=> $request->input('pemeriksaan_penunjang.limfosit'),
            "trombosit"=> $request->input('pemeriksaan_penunjang.trombosit'),
            "ventilator"=> $request->input('pemeriksaan_penunjang.ventilator'),
            "status_kesehatan"=> $request->input('pemeriksaan_penunjang.status_kesehatan'),
            "keterangan_lab"=> $request->input('pemeriksaan_penunjang.keterangan_lab'),
        ];
    }

    private function getRequestRiwayatKontak(Request $request) : array 
    {
        return $request->input('riwayat_kontak');
    }

    private function getRequestRiwayatLawatan(Request $request) : array
    {
        return $request->input('riwayat_lawatan');
    }

    private function getRequestPenyakitPenyerta(Request $request) : array
    {
        return [
            'keterangan_lain'=> $request->input('penyakit_penyerta.keterangan_lain'),
            'daftar_penyakit'=> $request->input('penyakit_penyerta.riwayat'),
        ];
    }

    public function delete($id, $pasien)
    {
        DB::beginTransaction();
        try{
            PasienRegister::where('register_id',$id)->where('pasien_id',$pasien)->delete();
            $sampel = Sampel::where('register_id',$id)->delete();
            $register = Register::where('id',$id)->delete();
            $pasien = Pasien::where('id',$pasien)->delete();
            DB::commit();
            return response()->json([
                'status'=> true,
                'message'=>"Berhasil menghapus data register"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function destroy(Register $register)
    {

        DB::beginTransaction();
        try {

            $register->pasiens()->detach();

            $register->riwayatKunjungan()->delete();

            $register->gejalaPasien()->detach();

            $register->pemeriksaanPenunjang()->detach();

            $register->riwayatLawatan()->detach();

            $register->riwayatKontak()->detach();

            $register->riwayatPenyakitPenyerta()->delete();

            $register->delete();
            
            DB::commit();

            return response()->json([
                'status'=> true,
                'message'=> __("Berhasil menghapus data register")
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
