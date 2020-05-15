<?php

namespace App\Imports;

use App\Models\Fasyankes;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\Sampel;
use App\Traits\RegisterTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

                $registerData = [
                    // 'id'=> $omgRegisterId,
                    'sumber_pasien'=> $row->get('kategori'),
                    'register_uuid'=> (string) \Illuminate\Support\Str::uuid(),
                    'jenis_registrasi'=> 'mandiri',
                    'nomor_register'=> $this->generateNomorRegister(),
                    // 'nomor_register'=> "20200425L" . str_pad($counterNomorRegister, 4, "0", STR_PAD_LEFT),
                    'creator_user_id' => auth()->user()->id,
                    'hasil_rdt'=> $row->get('hasil_rdt'),
                    'kunjungan_ke'=> $row->get('kunjungan'),
                    'tanggal_kunjungan'=> $row->get('tanggal_kunjungan'),
                    'rs_kunjungan'=> $row->get('rs_kunjungan')
                ];

                Validator::make($registerData, [
                    'tanggal_kunjungan'=> 'date|date_format:Y-m-d'
                 ])->validate();

                $register = Register::create($registerData);

                $pasienData = [
                    'nik'=> $this->parseNIK($row->get('nik')),
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
                    'sumber_pasien'=> $row->get('sumber_pasien'),
                    'usia_tahun'=> $row->get('usia_tahun'),
                    'usia_bulan'=> $row->get('usia_bulan')
                ];

                Validator::make($pasienData, [
                    'nik'=> 'required|digits:16',
                    'nama_lengkap'=> 'required|min:3',
                    'tanggal_lahir'=> 'required|date|date_format:Y-m-d',
                    'kota_id'=> 'required|exists:kota,id',
                    'kewarganegaraan'=> 'required',
                    'jenis_kelamin'=> 'required|in:L,P',
                    'suhu'=> ['nullable', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/']
                 ],[
                     'nik.digits'=> 'NIK harus 16 dijit.', 
                     'kota_id.required'=> 'Kota harap diisi dengan menyesuaikan dengan ID di Sheet Kota',
                     'kota_id.exists'=> 'Kota tidak ditemukan',
                     'suhu.regex'=> 'Angka suhu tidak valid'
                 ])->validate();

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
