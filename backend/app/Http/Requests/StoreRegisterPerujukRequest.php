<?php

namespace App\Http\Requests;

use App\Rules\RequiredKeteranganWarganegara;
use App\Rules\RequiredNamaJenisSampel;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterPerujukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->perujuk_id ? true : false;
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
                new UniqueSampelPerujuk($this->lab_satelit_id),
            ],
            'jenis_sampel' => 'required',
            'nama_jenis_sampel' => [
                new RequiredNamaJenisSampel($this->jenis_sampel),
            ],
            'fasyankes_id' => 'required',
            'fasyankes_pengirim' => 'required',
            'nama_pasien' => 'required',
            'kewarganegaraan' => 'required',
            'keterangan_warganegara' => [
                'required',
                new RequiredKeteranganWarganegara($this->kewarganegaraan)
            ],
            'nik' => 'nullable|digits:16',
            'no_hp' => 'required'
        ];
    }
}
