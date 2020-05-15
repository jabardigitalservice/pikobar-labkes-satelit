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

class RegisterWebSatelitController extends Controller
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
            $kode_registrasi = 'L';
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

    public function storeWebSatelit(Request $request)
    {
        $user = Auth::user();
        $v = Validator::make($request->all(),[
            'reg_kewarganegaraan' => 'required',
            // 'reg_sumberpasien' => 'required',
            'reg_nama_pasien' => 'required',
            'reg_nik'  => 'max:16',
            // 'reg_tempatlahir' => 'required',
            // 'reg_tgllahir' => 'required',
            'reg_nohp' => 'required|max:15',
            'reg_kota' => 'required',
            'reg_alamat' => 'required',
            'reg_jk'=>'required',
            'reg_sampel.*' => [
                'required',
                new UniqueSampel(),
            ],
            // 'reg_tanggalkunjungan' => 'required',
            // 'reg_kunke' => 'required',
            // 'reg_rsfasyankes' => 'required',
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
            'reg_alamat' => 'Alamat tidak boleh kosong',
            'reg_sampel.required' => 'Mohon isi minimal satu nomor sampel',
            'reg_tanggalkunjungan' => 'Mohon isi form tanggal kunjungan',
            'reg_kunke' => 'Mohon isi kunjungan keberapa',
            'reg_rsfasyankes' => 'Mohon isi form',
            'reg_jk' => 'Mohon pIlih Jenis Kelamin',
        ]);
        $v->validate();
        // foreach($request->get('reg_sampel') as $rows){
        //     echo "$rows[nomor]";
        // }
        // return Str::uuid();
        $nomor_register = $request->input('reg_no');
        if (Register::where('nomor_register', $nomor_register)->exists()) {
            $nomor_register = $this->generateNomorRegister();
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
            'tanggal_kunjungan' => $request->get('reg_tanggalkunjungan'),
            'kunjungan_ke' => $request->get('reg_kunke'),
            'rs_kunjungan' => $request->get('reg_rsfasyankes'),
            'hasil_rdt' => $request->get('reg_hasil_rdt'),
            'sumber_pasien' => $request->get('reg_sumberpasien')=="Umum"?"Umum":$request->get('reg_sumberpasien_isian'),
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
        $pasien->usia_tahun = $request->get('reg_usia_tahun');
        $pasien->usia_bulan = $request->get('reg_usia_bulan');
        $pasien->save();

        // $tandaGejala = $this->getRequestTandaGejala($request);

        $regis = PasienRegister::create([
            'pasien_id' => $pasien->id,
            'register_id' => $register->id,
        ]);

        if($request->get('reg_sampel')) {
            foreach($request->get('reg_sampel') as $rows) {
                $sampel = new Sampel;
                $sampel->nomor_sampel = $rows['nomor'];
                $sampel->nomor_register = $nomor_register;
                $sampel->register_id = $register->id;
                $sampel->sampel_status = 'waiting_sample';
                $sampel->updateState('waiting_sample');
                $sampel->save();
            }
        }

        return response()->json(['status'=>201,'message'=>'Proses Registrasi Web Satelit Berhasil Ditambahkan','result'=>[]]);
    }

    public function storeUpdate(Request $request,$regis_id, $pasien_id)
    {
        $register = Register::where('id',$regis_id)->first();
        $pasien   = Pasien::where('id',$pasien_id)->first();
        $user = Auth::user();
        $v = Validator::make($request->all(),[
            'reg_kewarganegaraan' => 'required',
            'reg_sumberpasien' => 'required',
            'reg_nama_pasien' => 'required',
            'reg_nik'  => 'max:16',
            'reg_nohp' => 'required|max:15',
            'reg_kota' => 'required',
            'reg_alamat' => 'required',
            'reg_jk'=>'required',
            // 'reg_sampel.*' => [
            //     'required',
            //     new UniqueSampel(),
            // ],
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
            'reg_sampel.required' => 'Mohon isi minimal satu nomor sampel'
        ]);
        $v->validate();
        
        $register->sumber_pasien = $request->get('reg_sumberpasien');
        $register->tanggal_kunjungan = $request->get('reg_tanggalkunjungan');
        $register->kunjungan_ke = $request->get('reg_kunke');
        $register->rs_kunjungan = $request->get('reg_rsfasyankes');
        $register->hasil_rdt = $request->get('reg_hasil_rdt');
        $register->sumber_pasien = $request->get('reg_sumberpasien')=="Umum"?"Umum":$request->get('reg_sumberpasien_isian');
        $register->save();

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
                if($rows['id']!=null){
                    $sampel = Sampel::where('id',$rows['id'])->first();
                }else {
                    $sampel = new Sampel;
                }
                $sampel->nomor_sampel = $rows['nomor'];
                $sampel->nomor_register = $request->input('reg_no');
                $sampel->register_id = $register->id;
                $sampel->save();
            }
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
                'id' => $sm->id
            ));
            // $smp['nomor'] = $sm->nomor_sampel;
            // $smp['id'] = $sm->id;
        }
        return response()->json([
            'reg_no' =>  $register->nomor_register,
            'reg_kewarganegaraan' =>  $pasien->kewarganegaraan,
            'reg_nama_pasien' =>  $pasien->nama_lengkap,
            // 'reg_nik' =>  $pasien->nik,
            'reg_tempatlahir' =>  $pasien->tempat_lahir,
            'reg_tgllahir' =>  $pasien->tanggal_lahir,
            'reg_nohp' =>  $pasien->no_hp,
            'reg_kota' =>  $pasien->kota_id,
            'reg_kecamatan' =>  $pasien->kecamatan,
            'reg_kelurahan' =>  $pasien->kelurahan,
            'reg_alamat' =>  $pasien->alamat_lengkap,
            'reg_rt' =>  $pasien->no_rt,
            'reg_rw' =>  $pasien->no_rw,
            'reg_suhu' =>  $pasien->suhu,
            'reg_sampel' =>  $smp,
            'reg_keterangan' =>  $pasien->keterangan_lain,
            'reg_nik'=> $pasien->nik,
            'reg_gejpanas' => null,
            'reg_gejpenumonia' => null,
            'reg_gejbatuk' => null,
            'reg_gejnyeritenggorokan' => null,
            'reg_gejsesaknafas' => null,
            'reg_gejpilek' => null,
            'reg_gejlesu' => null,
            'reg_gejsakitkepala' => null,
            'reg_gejdiare' => null,
            'reg_gejmualmuntah' => null,
            'reg_gejlain' => null,
            'reg_jk' => $pasien->jenis_kelamin,
            'nama_kota'=>$pasien->nama,
            'kunjungan_ke' =>$register->kunjungan_ke,
            'tanggal_kunjungan' => $register->tanggal_kunjungan,
            'rs_kunjungan' => $register->rs_kunjungan,
            'reg_hasil_rdt' => $register->hasil_rdt,
            'reg_usia_tahun' => $pasien->usia_tahun,
            'reg_usia_bulan' => $pasien->usia_bulan,
            'reg_sumberpasien' =>  $register->sumber_pasien=="Umum"?"Umum":"Other",
            "reg_sumberpasien_isian" => $register->sumber_pasien=="Umum"?null:$register->sumber_pasien,
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
