<?php

namespace App\Http\Requests;

use App\Enums\JenisSampelEnum;
use App\Enums\KewarganegaraanEnum;
use App\Enums\KriteriaEnum;
use App\Models\Sampel;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class RegisterSampelRequest extends FormRequest
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
        $user = Auth::user();
        $sampel = null;
        if (isset($this->register->sampel)) {
            $sampel = $this->register->sampel;
        }
        return [
            'reg_fasyankes_id' => 'required|exists:labkes.fasyankes,id',
            'reg_nama_pasien' => 'required',
            'reg_nik' => 'required|digits:16',
            'reg_sampel_nomor' => [
                'required',
                new UniqueSampelPerujuk($user->lab_satelit_id, optional($sampel)->id)
            ],
            'reg_sumber_pasien' => 'required',
            'reg_nohp' => 'required',
            'reg_sampel_jenis_sampel' => 'required|exists:labkes.jenis_sampel,id',
            'reg_sampel_namadiluarjenis' => 'required_if:reg_sampel_jenis_sampel,' . JenisSampelEnum::LAINNYA()->getIndex(),
            'reg_kewarganegaraan' => ['required', new EnumValueRule(KewarganegaraanEnum::class)],
            'reg_keterangan_warganegara' => 'required_if:kewarganegaraan,' . KewarganegaraanEnum::WNA()->getValue(),
            'reg_status' => ['nullable', new EnumIndexRule(KriteriaEnum::class)],
            'reg_swab_ke' => 'nullable',
            'reg_tanggal_swab' => 'nullable|date|date_format:Y-m-d',
            'reg_tgllahir' => 'nullable|date|date_format:Y-m-d',
            'reg_tempatlahir' => 'nullable',
            'reg_nohp' => 'required|numeric',
            'reg_kode_provinsi' => 'required|numeric|exists:labkes.provinsi,id',
            'reg_kode_kota' => 'required|numeric|exists:labkes.kota,id',
            'reg_kode_kecamatan' => 'nullable|numeric|exists:labkes.kota,id',
            'reg_kode_kelurahan' => 'nullable|numeric|exists:labkes.kota,id',
            'reg_rt' => 'nullable',
            'reg_rw' => 'nullable',
            'reg_jk' => ['nullable', new EnumValueRule(JenisSampelEnum::class)],
            'reg_keterangan' => 'nullable',
            'reg_usia_tahun' => 'nullable|integer',
            'reg_usia_bulan' => 'nullable|integer',
            'reg_pelaporan_id' => 'nullable',
            'reg_pelaporan_id_case' => 'nullable',
            'reg_alamat' => 'required'
        ];
    }
}
