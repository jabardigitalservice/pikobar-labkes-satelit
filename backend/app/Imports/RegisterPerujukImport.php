<?php

namespace App\Imports;

use App\Enums\JenisSampelEnum;
use App\Enums\KriteriaEnum;
use App\Models\JenisSampel;
use App\Models\RegisterPerujuk;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterPerujukImport implements WithHeadingRow, WithChunkReading, WithBatchInserts, ToModel, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        return RegisterPerujuk::create($row + $this->mappingRegisterPerujuk($row));
    }

    public function getJenisSampelId($jenis_sampel)
    {
        $jenis_sampel = JenisSampel::where('nama', 'ilike', '%' . $jenis_sampel . '%')->first();
        $jenis_sampel_id = optional($jenis_sampel)->id ?? JenisSampelEnum::LAINNYA()->getIndex();
        return $jenis_sampel_id;
    }

    public function mappingRegisterPerujuk($row): array
    {
        return [
            'nomor_sampel' => $row['kode_sampel'],
            'nomor_register' => generateNomorRegister(),
            'register_uuid' => Str::uuid(),
            'creator_user_id' => Auth::user()->id,
            'perujuk_id' => Auth::user()->perujuk_id,
            'sumber_pasien' => $row['kategori'],
            'kriteria' => $row['kriteria'],
            'nama_jenis_sampel' => $row['jenis_sampel'],
            'jenis_sampel' => $this->getJenisSampelId($row['jenis_sampel']),
            'fasyankes_id' => $row['kode_instansi'],
            'fasyankes_pengirim' => $row['instansi_pengirim'],
            'nama_pasien' => $row['nama'],
            'tanggal_lahir' => $row['tgl_lahir'],
            'usia_tahun' => $row['usia'],
            'provinsi_id' => $row['kode_provinsi'],
            'kota_id' => $row['kode_kotakab'],
            'kecamatan_id' => $row['kode_kecamatan'],
            'kelurahan_id' => $row['kode_kelurahan'],
            'created_at' => $row['tgl_masuk_sampel']
        ];
    }

    public function rules(): array
    {
        return [
            '*.tgl_masuk_sampel' => 'required|date|date_format:Y-m-d',
            '*.lab_satelit_id' => 'required|exists:lab_satelit,id',
            '*.kode_sampel' => ['required', new UniqueSampelPerujuk($this->lab_satelit_id),],
            '*.kewarganegaraan' => 'required',
            '*.kategori' => 'required',
            '*.no_hp' => 'required',
            '*.nama' => 'required',
            '*.nik' => 'nullable|digits:16',
            '*.jenis_kelamin' => 'nullable',
            '*.tgl_lahir' => 'nullable|date|date_format:Y-m-d',
            '*.usia' => 'nullable|integer',
            '*.alamat' => 'nullable',
            '*.kode_kelurahan' => 'nullable|exists:labkes.kelurahan,id',
            '*.kode_kecamatan' => 'nullable|exists:labkes.kecamatan,id',
            '*.kode_kotakab' => 'nullable|exists:labkes.kota,id',
            '*.kode_provinsi' => 'nullable|exists:labkes.provinsi,id',
            '*.kriteria' => ['required', new EnumValueRule(KriteriaEnum::class)],
            '*.instansi_pengirim' => 'nullable',
            '*.kode_instansi' => 'required|exists:labkes.fasyankes,id',
            '*.jenis_sampel' => 'required',
            '*.swab_ke' => 'nullable|integer',
            '*.tanggal_swab' => 'nullable|date|date_format:Y-m-d',
        ];
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
