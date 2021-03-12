<?php

namespace App\Imports;

use App\Enums\JenisSampelEnum;
use App\Enums\KriteriaEnum;
use App\Models\Fasyankes;
use App\Models\JenisSampel;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Provinsi;
use App\Models\Register;
use App\Models\Sampel;
use App\Rules\UniqueSampelPerujuk;
use App\Traits\RegisterTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterSampelImport implements OnEachRow, WithHeadingRow, WithChunkReading, WithValidation
{
    use RegisterTrait;
    use Importable;

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $user = Auth::user();
        $row['creator_user_id'] = $user->id;
        $row['lab_satelit_id'] = $user->lab_satelit_id;
        $register = $this->createRegister($row);
        $pasien = $this->createPasien($row);
        PasienRegister::create(['pasien_id' => $pasien->id, 'register_id' => $register->id]);
        $pengambilan_sampel = $this->createPengambilanSampel();
        $this->createSampel($row, $register, $pengambilan_sampel);
    }

    private function createRegister($row)
    {
        $fasyankes = $this->getFasyankes($row['kode_instansi']);
        $fasyankesNama = $fasyankes->nama;
        $fasyankesTipe = $fasyankes->tipe;
        $row['nomor_registrasi'] = generateNomorRegister();
        $row['register_uuid'] = Str::uuid();
        $row['fasyankes_id'] = $row['kode_instansi'];
        $row['fasyankes_pengirim'] = $fasyankesTipe;
        $row['nama_rs'] = $fasyankesNama;
        $row['instansi_pengirim'] = $fasyankesTipe;
        $row['instansi_pengirim_nama'] = $fasyankesNama;
        $row['sumber_pasien'] = $row['kategori'];
        $row['status'] = $row['kriteria'];
        return Register::create($row);
    }

    private function createPasien($row)
    {
        $provinsi = $this->getWilayah('provinsi', $row->get('kode_provinsi'));
        $kota = $this->getWilayah('kota', $row->get('kode_kotakab'));
        $kecamatan = $this->getWilayah('kecamatan', $row->get('kode_kecamatan'));
        $kelurahan = $this->getWilayah('kelurahan', $row->get('kode_kelurahan'));
        $row['nama_lengkap'] = $row['nama'];
        $row['tanggal_lahir'] = $row['tgl_lahir'];
        $row['kode_provinsi'] = $provinsi->id;
        $row['nama_provinsi'] = $provinsi->nama;
        $row['kota_id'] = $kota->id;
        $row['kode_kabupaten'] = $kota->id;
        $row['nama_kabupaten'] = $kota->nama;
        $row['kecamatan'] = optional($kecamatan)->nama;
        $row['kode_kecamatan'] = optional($kecamatan)->id;
        $row['nama_kecamatan'] = optional($kecamatan)->nama;
        $row['kelurahan'] = optional($kelurahan)->nama;
        $row['kode_kelurahan'] = optional($kelurahan)->id;
        $row['nama_kelurahan'] = optional($kelurahan)->nama;
        $row['alamat_lengkap'] = $row['alamat'];
        $row['sumber_pasien'] = $row['kategori'];
        $row['jenis_kelamin'] = $row['jenis_kelamin'];
        $row['usia_tahun'] = $row['usia'];
        return Pasien::create($row);
    }

    private function createPengambilanSampel()
    {
        return PengambilanSampel::create([
            'sampel_diambil' => false,
            'sampel_diterima' => false,
            'diterima_dari_faskes' => false,
            'sampel_rdt' => false,
            'catatan' => null,
        ]);
    }

    private function createSampel($row, $register, $pengambilan_sampel)
    {
        $jenis_sampel = JenisSampel::where('nama', $row->get('jenis_sampel'))->first();
        $row['nomor_sampel'] = $row['kode_sampel'];
        $row['jenis_sampel_nama'] = $row['jenis_sampel'];
        $row['jenis_sampel_id'] = optional($jenis_sampel)->id ?? JenisSampelEnum::LAINNYA()->getIndex();
        $row['register_id'] = $register->id;
        $row['pengambilan_sampel_id'] = $pengambilan_sampel->id;
        $row['sampel_status'] = 'sample_taken';
        $row['created_at'] = $row['tgl_masuk_sampel'] . ' ' . date('H:i:s');
        $row['waktu_sample_taken'] = $row['tgl_masuk_sampel'] . ' ' . date('H:i:s');
        Sampel::create($row);
    }

    public function rules(): array
    {
        $user = Auth::user();
        return [
            '*.tgl_masuk_sampel' => 'required|date|date_format:Y-m-d',
            '*.kode_sampel' =>  [
                'required',
                new UniqueSampelPerujuk($user->lab_satelit_id)
            ],
            '*.kategori' => 'required',
            '*.nama' => 'required',
            '*.nik' => 'nullable|digits:16',
            '*.tgl_lahir' => 'nullable|date|date_format:Y-m-d',
            '*.kriteria' => [
                'nullable',
                new EnumValueRule(KriteriaEnum::class)
            ],
            '*.kode_instansi' => 'required|numeric|exists:labkes.fasyankes,id',
            '*.jenis_sampel' => 'required',
            '*.swab_ke' => 'nullable|numeric',
            '*.tanggal_swab' => 'nullable|date|date_format:Y-m-d',
            '*.kode_provinsi' => 'required|integer|exists:labkes.provinsi,id',
            '*.kode_kotakab' => 'required|integer|exists:labkes.kota,id',
            '*.kode_kecamatan' => 'nullable|integer|exists:labkes.kecamatan,id',
            '*.kode_kelurahan' => 'nullable|integer|exists:labkes.kelurahan,id',
            '*.kewarganegaraan' => 'nullable',
            '*.jenis_kelamin' => 'nullable'
        ];
    }

    private function getWilayah($tingkat, $id_wilayah)
    {
        $namaWilayah = null;
        if (!$id_wilayah) {
            return $namaWilayah;
        }
        switch ($tingkat) {
            case 'provinsi':
                $namaWilayah = Provinsi::find($id_wilayah);
                break;
            case 'kota':
                $namaWilayah = Kota::find($id_wilayah);
                break;
            case 'kecamatan':
                $namaWilayah = Kecamatan::find($id_wilayah);
                break;
            case 'kelurahan':
                $namaWilayah = Kelurahan::find($id_wilayah);
                break;
        }
        return $namaWilayah;
    }

    private function getFasyankes($fasyankes)
    {
        return Fasyankes::find($fasyankes);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
