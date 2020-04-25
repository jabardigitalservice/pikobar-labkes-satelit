<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRegisterRujukanRequest;
use App\Http\Requests\EditRegisterRujukanRequest;
use App\Http\Resources\RegisterRujukanResource;
use App\Models\Pasien;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\RiwayatKunjungan;
use App\Models\RiwayatPenyakitPenyerta;
use App\Models\Sampel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterRujukanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateRegisterRujukanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRegisterRujukanRequest $request)
    {
        $request->validated();

        $sampelPayload = $request->only('sampel');

        $pengambilanSampel = PengambilanSampel::whereHas('sampel', function(Builder $query) use($sampelPayload){
            $query->whereIn('nomor_barcode', collect($sampelPayload['sampel'])->flatten());
        })->get();

        if ($pengambilanSampel->count() > 1) {
            abort(422, __("Sampel tidak dalam pengambilan yang sama."));
        }

        $sampelWithoutPengambilan = Sampel::doesntHave('pengambilanSampel')
            ->whereIn('nomor_barcode', collect($sampelPayload['sampel'])->flatten())
            ->get();

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

                abort(422, __("Gagal menambah data. Pasien dengan nomor identitas tersebut telah tersedia."));
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

            $pengambilan = $pengambilanSampel->count() ? $pengambilanSampel->first() : null;

            if (!$pengambilan && collect($sampelPayload['sampel'])->count()) {
                $pengambilan = PengambilanSampel::create([
                    'sampel_diterima'=> true,
                    'diterima_dari_faskes'=> false,
                    'status'=> 'active',
                    'sampel_rdt'=> false
                ]);

                $sampelCollection = Sampel::whereIn('nomor_barcode', collect($sampelPayload['sampel'])->flatten())->get();
                $pengambilan->sampel()->saveMany($sampelCollection);                
            }

            if ($sampelWithoutPengambilan->count()) {
                $pengambilan->sampel()->saveMany($sampelWithoutPengambilan);                
            }

            if ($pengambilan->register->count()) {
                abort(422, __("Sampel sudah terdaftar untuk register pasien."));
            }

            // Save within register
            $pengambilan->register()->attach($register);
            
            DB::commit();

            return new RegisterRujukanResource($register);


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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function show(Register $register)
    {
        abort_if(!$register->pengambilanSampel->count(), 422, __("Tidak ada data register rujukan."));

        return new RegisterRujukanResource($register);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditRegisterRujukanRequest  $request
     * @param  \App\Models\Register  $register
     * @return \Illuminate\Http\Response
     */
    public function update(EditRegisterRujukanRequest $request, Register $register)
    {
        $request->validated();

        abort_if(
            !$register->pengambilanSampel->count(), 
            422, 
            __("Tidak ada data register rujukan.")
        );

        // $pengambilanSampel = PengambilanSampel::whereHas('sampel', function(Builder $query) use($sampelPayload){
        //     $query->whereIn('nomor_barcode', collect($sampelPayload['sampel'])->flatten());
        // })->get();

        DB::beginTransaction();
        try {

            $register->gejalaPasien()->detach();
            
            $register->pemeriksaanPenunjang()->detach();
            
            $register->riwayatLawatan()->detach();
            
            $register->riwayatKontak()->detach();

            // $register->pengambilanSampel()->detach();
            
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

            return new RegisterRujukanResource($updatedRegister);

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
        abort_if(
            !$register->pengambilanSampel->count(), 
            422, 
            __("Tidak ada data register rujukan.")
        );

        DB::beginTransaction();
        try {

            $register->pasiens()->detach();

            $register->riwayatKunjungan()->delete();

            $register->gejalaPasien()->detach();

            $register->pemeriksaanPenunjang()->detach();

            $register->riwayatLawatan()->detach();

            $register->riwayatKontak()->detach();

            $register->riwayatPenyakitPenyerta()->delete();

            $register->pengambilanSampel()->detach();

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
