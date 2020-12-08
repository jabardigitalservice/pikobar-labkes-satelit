<?php

namespace App\Imports;

use App\Enums\JenisSampelEnum;
use App\Models\JenisSampel;
use App\Models\RegisterPerujuk;
use App\Rules\ExistsFasyankes;
use App\Rules\ExistsLabSatelit;
use App\Rules\ExistsWilayah;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegisterPerujukImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            foreach ($rows as $row) {
                if (!$row->get('no')) {
                    continue;
                }
                Validator::make($row->toArray(), [
                    'tgl_masuk_sampel' => 'required|date|date_format:Y-m-d',
                    'lab_satelit_id' => [
                        'required',
                        new ExistsLabSatelit,
                    ],
                    'kode_sampel' => [
                        'required',
                        new UniqueSampelPerujuk($row->get('lab_satelit_id')),
                    ],
                    'kewarganegaraan' => 'required',
                    'kategori' => 'required',
                    'no_hp' => 'required',
                    'nama' => 'required',
                    'nik' => 'nullable|digits:16',
                    'jenis_kelamin' => 'nullable',
                    'tgl_lahir' => 'nullable|date|date_format:Y-m-d',
                    'usia' => 'nullable|integer',
                    'alamat' => 'nullable',
                    'kode_kelurahan' => [
                        'nullable',
                        new ExistsWilayah(),
                    ],
                    'kode_kecamatan' => [
                        'nullable',
                        new ExistsWilayah(),
                    ],
                    'kode_kotakab' => [
                        'nullable',
                        new ExistsWilayah(),
                    ],
                    'kode_provinsi' => [
                        'nullable',
                        new ExistsWilayah(),
                    ],
                    'kriteria' => 'required|in:Kontak Erat,Suspek,Probable,Konfirmasi,Tanpa Kriteria',
                    'instansi_pengirim' => 'nullable',
                    'kode_instansi' => [
                        'required',
                        new ExistsFasyankes,
                    ],
                    'jenis_sampel' => 'required',
                    'swab_ke' => 'nullable|integer',
                    'tanggal_swab' => 'nullable|date|date_format:Y-m-d',
                ])->validate();

                $data = new RegisterPerujuk();
                $data->nomor_register = generateNomorRegister();
                $data->register_uuid = (string)Str::uuid();
                $data->creator_user_id = $user->id;
                $data->lab_satelit_id = $row->get('lab_satelit_id');
                $data->perujuk_id = $user->perujuk_id;
                $data->sumber_pasien = $row->get('kategori');
                $data->kriteria = $row->get('kriteria') ? array_search($row->get('kriteria'), STATUSES) : null;
                $data->swab_ke = $row->get('swab_ke');
                if ($row->get('tanggal_swab')) {
                    $data->tanggal_swab = date('Y-m-d', strtotime($row->get('tanggal_swab')));
                }
                $data->nomor_sampel = $row->get('kode_sampel');
                $data->nama_jenis_sampel = $row->get('jenis_sampel');
                $jenis_sampel = JenisSampel::where('nama', 'ilike', '%' . $row->get('jenis_sampel') . '%')->first();
                $data->jenis_sampel = $jenis_sampel ? $jenis_sampel->id : JenisSampelEnum::LAINNYA()->getIndex();
                $data->fasyankes_id = $row->get('kode_instansi');
                $data->fasyankes_pengirim = $row->get('instansi_pengirim');
                $data->nama_pasien = $row->get('nama');
                $data->no_hp = $row->get('no_hp');
                $data->kewarganegaraan = $row->get('kewarganegaraan');
                $data->nik = $row->get('nik');
                if ($row->get('tgl_lahir')) {
                    $data->tanggal_lahir = date('Y-m-d', strtotime($row->get('tgl_lahir')));
                }
                $data->provinsi_id = $row->get('kode_provinsi');
                $data->kota_id = $row->get('kode_kotakab');
                $data->kecamatan_id = $row->get('kode_kecamatan');
                $data->kelurahan_id = $row->get('kode_kelurahan');
                $data->alamat = $row->get('alamat');
                $data->jenis_kelamin = $row->get('jenis_kelamin');
                $data->usia_tahun = $row->get('usia');
                $data->created_at = date('Y-m-d H:i:s', strtotime($row->get('tgl_masuk_sampel').' '. date('H:i:s')));
                $data->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
