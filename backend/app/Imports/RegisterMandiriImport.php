<?php

namespace App\Imports;

use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\Sampel;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegisterMandiriImport implements ToCollection, WithHeadingRow
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
            
            foreach ($rows as $key => $row) {

                if (!$row->get('no')) {
                    continue;
                }

                $register = Register::create([
                    // 'id'=> $omgRegisterId,
                    'sumber_pasien'=> $row->get('sumber_pasien'),
                    'register_uuid'=> (string) \Illuminate\Support\Str::uuid(),
                    'jenis_registrasi'=> 'mandiri',
                    'nomor_register'=> $this->generateNomorRegister(),
                    // 'nomor_register'=> "20200425L" . str_pad($counterNomorRegister, 4, "0", STR_PAD_LEFT),
                    'creator_user_id' => auth()->user()->id,

                ]);

                $pasienData = [
                    'nik'=> $row->get('nik'),
                    'nama_lengkap'=> $row->get('nama_pasien'),
                    'kewarganegaraan'=> $row->get('kewarganegaraan'),
                    'jenis_kelamin'=> $row->get('jenis_kelamin'),
                    'tanggal_lahir'=> $row->get('tanggal_lahir'),
                    'tempat_lahir'=> $row->get('tempat_lahir'),
                    'kota_id'=> optional($this->getKota($row))->id,
                    'kecamatan'=> $row->get('kecamatan'),
                    'kelurahan'=> $row->get('kelurahan'),
                    'no_rw'=> $row->get('rw'),
                    'no_rt'=> $row->get('rt'),
                    'alamat_lengkap'=> $row->get('alamat'),
                    'keterangan_lain'=> $row->get('keterangan'),
                    'suhu'=> $row->get('suhu'),
                    'sumber_pasien'=> $row->get('sumber_pasien')
                ];

                $pasien = Pasien::query()->updateOrCreate(
                    \Illuminate\Support\Arr::only($pasienData, ['nik']),
                    $pasienData
                );

                $register->pasiens()->attach($pasien);

                $nomorSampels = explode(';', $row->get('nomor_sampel'));
                
                foreach ($nomorSampels as $key => $nomor) {
                    $sampelData = [
                        'nomor_sampel'=> $nomor,
                        'nomor_register'=> $register->getAttribute('nomor_register'),
                        // 'register_id'=> $register->getAttribute('id'),
                        'jenis_sampel_id',
                        'sampel_status'=> 'waiting_sample',
                        'creator_user_id'=> auth()->user()->id,
                        'waktu_waiting_sample'=> now()
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
        $kota = Kota::find($row->get('kota_id'));

        if (!$kota) {
            $kotaId = (int) substr(($row->get('nik')), 0, 4);
            $kota = Kota::find($kotaId);

        }

        abort_if(!$kota, 422, "Kota domisili tidak ditemukan pada pasien dengan NIK {$row->get('nik')}");


        return $kota;
    }

    

}
