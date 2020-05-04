<?php

namespace App\Imports;

use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Register;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class RegisterMandiriImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        

        DB::beginTransaction();
        try {

            $lastRegister = Register::query()->orderBy('id', 'desc')->first();
            $lengthNumber = $lastRegister ? (strlen($lastRegister->nomor_register) - 9 ): null;
            $counterNomorRegister = $lastRegister ? ((int) substr($lastRegister->nomor_register, 9, $lengthNumber) + 1) : 1;

            foreach ($rows as $key=> $row) 
            {
                if ($key == 0) continue;                

                $register = Register::create([
                    // 'fasyankes_id',
                    'sumber_pasien'=> $row[2],
                    'register_uuid'=> (string) \Illuminate\Support\Str::uuid(),
                    'jenis_registrasi'=> 'mandiri',
                    'nomor_register'=> "20200425L" . str_pad($counterNomorRegister, 4, "0", STR_PAD_LEFT),
                    'creator_user_id' => auth()->user()->id,

                ]);

                $pasienData = [
                    'nik'=> $row[4],
                    'nama_lengkap'=> $row[3],
                    'kewarganegaraan'=> strtolower($row[2]),
                    'jenis_kelamin'=> $row[7],
                    'tanggal_lahir'=> $row[6],
                    'tempat_lahir'=> $row[5],
                    'kota_id'=> optional($this->getKota($row->toArray()))->id,
                ];

                $pasien = Pasien::query()->updateOrCreate(
                    \Illuminate\Support\Arr::only($pasienData, ['nik']),
                    $pasienData
                );


                $register->pasiens()->attach($pasien);

                // $sampels 

                $counterNomorRegister++;

            }
            
            DB::commit();


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


    }


    private function getKota(array $row)
    {
        $kota = Kota::find($row[8]);

        if (!$kota) {
            $kotaId = substr($row[4], 0, 4);
            $kota = Kota::find($kotaId);

        }

        return $kota;
    }

    

}
