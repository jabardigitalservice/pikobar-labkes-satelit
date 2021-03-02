<?php

namespace App\Http\Requests;

use App\Enums\JenisSampelEnum;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Foundation\Http\FormRequest;

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
            'kriteria' => 'required',
            'swab_ke' => 'nullable|integer',
            'tanggal_swab' => 'nullable|date',
            'nomor_sampel' => [
                'required',
                new UniqueSampelPerujuk($this->lab_satelit_id, optional($this->register_perujuk)->id),
            ],
            'jenis_sampel' => 'required|exists:labkes.jenis_sampel,id',
            'nama_jenis_sampel' => 'required_if:jenis_sampel,' . JenisSampelEnum::LAINNYA()->getIndex(),
            'fasyankes_id' => 'required',
            'fasyankes_pengirim' => 'required',
            'nama_pasien' => 'required',
            'kewarganegaraan' => 'required',
            'keterangan_warganegara' => 'nullable|required_if:kewarnegaraan,WNA',
            'nik' => 'nullable|digits:16',
            'no_hp' => 'required',
            'lab_satelit_id' => 'required|exists:lab_satelit,id',
        ];
    }
}
