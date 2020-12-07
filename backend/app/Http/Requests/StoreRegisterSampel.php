<?php

namespace App\Http\Requests;

use App\Models\Sampel;
use App\Rules\UniqueSampelPerujuk;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRegisterSampel extends FormRequest
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
        return [
            'reg_fasyankes_id' => 'required',
            'reg_fasyankes_pengirim' => 'required',
            'reg_nama_rs' => 'required',
            'reg_nama_pasien' => 'required',
            'reg_nik' => 'nullable|digits:16',
            'reg_sampel_jenis_sampel' => 'required',
            'reg_sampel_nomor' => [
                'required',
                new UniqueSampelPerujuk($user->lab_satelit_id)
            ],
            'reg_sumber_pasien' => 'required',
            'reg_nohp' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $nomorsampel = Sampel::where('nomor_sampel', strtoupper($this->reg_sampel_nomor))
                ->where('lab_satelit_id', $user->lab_satelit_id)->first();
            if ($nomorsampel != null) {
                $validator->errors()->add("reg_sampel_nomor", 'Nomor Sampel sudah digunakan');
            }
            if ($this->reg_sampel_jenis_sampel == 999999 && $this->reg_sampel_namadiluarjenis == null) {
                $validator->errors()->add("reg_sampel_namadiluarjenis", 'Nama diluar jenis tidak boleh kosong');
            }
            if ($this->kewarganegaraan == 'WNA' && $this->keterangan_warganegara == null) {
                $validator->errors()->add("reg_keterangan_warganegara", 'Keterangan warganegara tidak boleh kosong');
            }
        });
    }
}
