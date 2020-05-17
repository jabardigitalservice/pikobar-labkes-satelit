<?php

namespace App\Imports;

use App\Models\Fasyankes;
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
                if (!$row->get('no') || !$row->get('nik')) {
                    continue;
                }

                $registerData = [
                    'register_uuid'=> (string) \Illuminate\Support\Str::uuid(),
                    'jenis_registrasi'=> 'mandiri',
                    'creator_user_id' => $user->id,
                    'lab_satelit_id' => $user->lab_satelit_id,
                    'created_at'=> date('Y-m-d H:i:s',strtotime($row->get('tgl_masuk_sampel').' '.date('H:i:s'))),
                    'instansi_pengirim'=> $row->get('instansi_pengirim'),
                    'instansi_pengirim_nama'=> $row->get('nama_instansi'),
                ];

                Validator::make($registerData, [
                    'created_at'=> 'date|date_format:Y-m-d H:i:s',
                 ])->validate();

                $register = Register::create($registerData);

                $pasienData = [
                    'nik'=> $this->parseNIK($row->get('nik')),
                    'nama_lengkap'=> $row->get('nama'),
                    'jenis_kelamin'=> $row->get('jenis_kelamin'),
                    'tanggal_lahir'=> date('Y-m-d',strtotime($row->get('tgl_lahir'))),
                    'kota_id'=> optional($this->getKota($row))->id,
                    'kecamatan'=> $row->get('kecamatan'),
                    'kelurahan'=> $row->get('desakelurahan'),
                    'alamat_lengkap'=> $row->get('alamat'),
                    'usia_tahun'=> $row->get('usia'),
                ];

                Validator::make($pasienData, [
                    'nik'=> 'required|digits:16',
                    'nama_lengkap'=> 'required|min:3',
                    'tanggal_lahir'=> 'required|date|date_format:Y-m-d',
                    'kota_id'=> 'required|exists:kota,id',
                    'jenis_kelamin'=> 'required|in:L,P',
                 ],[
                     'nik.digits'=> 'NIK harus 16 dijit.', 
                     'kota_id.required'=> 'Kota harap diisi dengan menyesuaikan dengan ID di Sheet Kota',
                     'kota_id.exists'=> 'Kota tidak ditemukan',
                 ])->validate();

                $pasien = Pasien::query()->updateOrCreate(
                    \Illuminate\Support\Arr::only($pasienData, ['nik']),
                    $pasienData
                );

                $register->pasiens()->attach($pasien);

                $nomorSampels = explode(';', $row->get('kode_sampel'));
                
                foreach ($nomorSampels as $key => $nomor) {
                    $sampelData = [
                        'nomor_sampel'=> $nomor,
                        'nomor_register'=> $register->getAttribute('nomor_register'),
                        'sampel_status'=> 'waiting_sample',
                        'lab_satelit_id'=> $user->lab_satelit_id,
                        'creator_user_id'=> $user->id,
                        'jenis_sampel_nama'=> $row->get('jenis_sampel'),
                        'created_at' => date('Y-m-d H:i:s',strtotime($row->get('tgl_masuk_sampel').' '.date('H:i:s')))
                    ];

                    $sampel = Sampel::query()->updateOrCreate(
                        \Illuminate\Support\Arr::only($sampelData, ['nomor_sampel']),
                        $sampelData
                    );

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
        $kota = Kota::find($row->get('kotakab'));
        if (!$kota) {
            $kotaId = (int) substr(($row->get('nik')), 0, 4);
            $kota = Kota::find($kotaId);

        }

        abort_if(!$kota, 403, "Kota domisili tidak ditemukan pada pasien dengan NIK {$row->get('nik')}");


        return $kota;
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
