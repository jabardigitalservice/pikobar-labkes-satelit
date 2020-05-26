<?php

namespace App\Imports;

use App\Models\Fasyankes;
use App\Models\JenisSampel;
use App\Models\Kota;
use App\Models\Pasien;
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

        // $lastRegister = Register::query()->orderBy('id', 'desc')->first();
        // $lengthNumber = $lastRegister ? (strlen($lastRegister->nomor_register) - 9 ): null;
        // $counterNomorRegister = $lastRegister ? ((int) substr($lastRegister->nomor_register, 9, $lengthNumber) + 1) : 1;

        // $omgRegisterId = $lastRegister ? (++$lastRegister->id) : 1;

        DB::beginTransaction();
        try {
            $user = Auth::user();
            foreach ($rows as $key => $row) {
                if (!$row->get('no')) {
                    continue;
                }
                $registerData = [
                    'register_uuid'=> (string) \Illuminate\Support\Str::uuid(),
                    'creator_user_id' => $user->id,
                    'lab_satelit_id' => $user->lab_satelit_id,
                    'created_at'=> $row->get('tgl_masuk_sampel'),
                    'instansi_pengirim'=> $row->get('instansi_pengirim'),
                    'instansi_pengirim_nama'=> $row->get('nama_instansi'),
                ];

                Validator::make($registerData, [
                    'instansi_pengirim'=> 'required',
                    'instansi_pengirim_nama'=> 'required',
                    'created_at'=> 'date|date_format:Y-m-d'
                ],[
                    'instansi_pengirim.required' => 'Instansi Pengirim tidak boleh kosong',
                    'instansi_pengirim_nama.required' => 'Nama Rumah Sakit/Dinkes tidak boleh kosong',
                    'created_at.date' => 'Tanggal Masuk Sampel tidak valid',
                    'created_at.date_format' => 'Format Tanggal Masuk Sampel harus yyyy-mm-dd',
                ]
                )->validate();

                $register = new Register;
                $register->register_uuid = (string) \Illuminate\Support\Str::uuid();
                $register->created_at = date('Y-m-d H:i:s',strtotime($row->get('tgl_masuk_sampel').' '.date('H:i:s')));
                $register->creator_user_id = $user->id;
                $register->lab_satelit_id = $user->lab_satelit_id;
                $register->instansi_pengirim = $row->get('instansi_pengirim');
                $register->instansi_pengirim_nama = $row->get('nama_instansi');
                $register->save();
                $pasienData = [
                    'nik'=> $row->get('nik'),
                    'nama_lengkap'=> $row->get('nama'),
                    'jenis_kelamin'=> $row->get('jenis_kelamin'),
                    'tanggal_lahir'=> $row->get('tgl_lahir'),
                    'kota_id'=> $this->getKota($row),
                    'kecamatan'=> $row->get('kecamatan'),
                    'kelurahan'=> $row->get('desakelurahan'),
                    'alamat_lengkap'=> $row->get('alamat'),
                    'usia_tahun'=> $row->get('usia'),
                    'lab_satelit_id'=> $user->lab_satelit_id,
                ];
                Validator::make($pasienData, [
                    'nik'=> 'nullable|digits:16',
                    'nama_lengkap'=> 'required',
                    'tanggal_lahir'=> 'nullable|date|date_format:Y-m-d'
                 ],[
                     'nik.digits'=> 'NIK terdiri dari 16 karakter', 
                     'nama_lengkap.required'=> 'Nama Pasien Tidak Boleh Kosong',
                     'tanggal_lahir.date' => 'Tanggal Lahir tidak valid',
                     'tanggal_lahir.date_format' => 'Format Tanggal Lahir harus yyyy-mm-dd', 
                 ])->validate();
                //  $pasien = Pasien::where('nik',$row->get('nik'))->first();
                //  if (!$pasien) {
                     $pasien = new Pasien;
                //  }
                 $pasien->nik = $this->parseNIK($row->get('nik'));
                 $pasien->nama_lengkap = $row->get('nama');
                 $pasien->jenis_kelamin = $row->get('jenis_kelamin');
                 $pasien->tanggal_lahir = date('Y-m-d',strtotime($row->get('tgl_lahir')));
                 $pasien->kota_id = $this->getKota($row);
                 $pasien->kecamatan = $row->get('kecamatan');
                 $pasien->kelurahan = $row->get('desakelurahan');
                 $pasien->alamat_lengkap = $row->get('alamat');
                 $pasien->usia_tahun = $row->get('usia');
                 $pasien->lab_satelit_id = $user->lab_satelit_id;
                 $pasien->save();

                $register->pasiens()->attach($pasien);

                $nomorSampels = explode(';', $row->get('kode_sampel'));
                $error = 0;
                foreach ($nomorSampels as $key => $nomor) {
                    abort_if($nomor == "", 403,"Nomor Sampel Tidak Boleh Kosong");
                    $jenissampel = JenisSampel::where('nama','ilike','%'.$row->get('jenis_sampel').'%')->first();
                    $nomorsampel = Sampel::where('nomor_sampel','ilike','%'.$nomor.'%')->where('lab_satelit_id',$user->lab_satelit_id)->first();
                    if ($nomorsampel) {
                        $error++;
                    }
                    
                    abort_if($error == count($nomorSampels), 403,"Nomor Sampel Sudah Terpakai {$nomor}");
                    
                    $sampel = new Sampel();
                    $sampel->nomor_sampel = strtoupper($nomor);
                    $sampel->sampel_status = 'sample_taken';
                    $sampel->lab_satelit_id = $user->lab_satelit_id;
                    $sampel->creator_user_id = $user->id;
                    $sampel->jenis_sampel_id = $jenissampel ? $jenissampel->id : 999999;
                    $sampel->jenis_sampel_nama = $row->get('jenis_sampel');
                    $sampel->created_at = date('Y-m-d H:i:s',strtotime($row->get('tgl_masuk_sampel').' '.date('H:i:s')));
                    $sampel->waktu_sample_taken = date('Y-m-d H:i:s',strtotime($row->get('tgl_masuk_sampel').' '.date('H:i:s')));
                    $sampel->save();

                    $register->sampel()->save($sampel);
                }

                // $counterNomorRegister++;
                // $omgRegisterId++;
            }


            DB::commit();


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }


    private function getKota(Collection $row)
    {
        $kotakab = $row->get('kotakab');
        $kota = Kota::where('id',$kotakab)->first();
        if (!$kota) {
            $kotaId = (int) substr(($row->get('nik')), 0, 4);
            $kota = Kota::where('id',$kotaId)->first();
        }

        // abort_if(!$kota, 403, "Kota domisili tidak ditemukan pada pasien dengan NIK {$row->get('nik')}");


        return $kota != null ? $kota->id : null;
    }

    private function parseNIK($nik)
    {
        if (!$nik) {
            return null;
        }

        if ($separated = explode("'", $nik)) {
            return count($separated) > 1 ? $separated[1] : (string) $nik;
        }

        return (string) $nik;
    }
    

}
