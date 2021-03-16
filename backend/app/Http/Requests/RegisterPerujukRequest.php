<?php

namespace App\Http\Requests;

use App\Enums\JenisKelaminEnum;
use App\Enums\JenisSampelEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\KriteriaEnum;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterPerujukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sumber_pasien' => 'required',
            'kriteria' => ['required', new EnumIndexRule(KriteriaEnum::class)],
            'swab_ke' => 'nullable|integer',
            'tanggal_swab' => 'nullable',
            'nomor_sampel' => [
                'required',
                new UniqueSampelPerujuk($this->lab_satelit_id, optional($this->register_perujuk)->id),
            ],
            'jenis_sampel' => 'required|exists:labkes.jenis_sampel,id',
            'nama_jenis_sampel' => 'required_if:jenis_sampel,' . JenisSampelEnum::LAINNYA()->getIndex(),
            'fasyankes_id' => 'required|integer|exists:labkes.fasyankes,id',
            'fasyankes_pengirim' => 'required',
            'nama_pasien' => 'required',
            'kewarganegaraan' => ['required', new EnumValueRule(KewarganegaraanEnum::class)],
            'keterangan_warganegara' => 'required_if:kewarnegaraan,' . KewarganegaraanEnum::WNA()->getValue(),
            'nik' => 'required|digits:16',
            'no_hp' => 'required',
            'lab_satelit_id' => 'required|exists:lab_satelit,id',
            'alamat' => 'required',
            'jenis_kelamin' => ['nullable', new EnumValueRule(JenisKelaminEnum::class)],
            'provinsi_id' => 'required|numeric|exists:labkes.provinsi,id',
            'kota_id' => 'required|numeric|exists:labkes.kota,id',
            'kecamatan_id' => 'required|numeric|exists:labkes.kecamatan,id',
            'kelurahan_id' => 'nullable|numeric|exists:labkes.kelurahan,id',
            'kode_kasus' => 'nullable',
            'no_rt' => 'nullable|max:3',
            'no_rw' => 'nullable|max:3',
            'tanggal_lahir' => 'nullable',
            'tempat_lahir' => 'nullable',
            'usia_bulan' => 'nullable',
            'usia_tahun' => 'nullable',
            'keterangan' => 'nullable',
            'nama_kecamatan' => 'nullable',
            'nama_kelurahan' => 'nullable',
            'nama_kota' => 'nullable',
            'nama_provinsi' => 'nullable'
        ];
    }
}
