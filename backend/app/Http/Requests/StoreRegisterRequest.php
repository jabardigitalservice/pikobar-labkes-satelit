<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegisterRequest extends FormRequest
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
            // table: register
            'nomor_register'=> ['required', 'uuid', 'unique:register,nomor_register'],
            'fasyankes_id'=> ['required', 'exists:fasyankes,id'],
            'nomor_rekam_medis'=> ['min:3', 'max:255'],
            'nama_dokter'=> ['required', 'max:255'],
            'no_telp'=> ['max:30'],

            // table: pasien
            "nama_depan"=> ['required', 'min:3', 'max:255'],
            "nama_belakang"=> ['min:3', 'max:255'],
            "kewarganegaraan"=> ['required', 'in:wni,wna'],
            "no_ktp"=> ['nullable', 'digits:16', 'unique:pasien,no_ktp'],
            "no_sim"=> [
                Rule::requiredIf(function(){
                    return !$this->input('no_ktp');
                }),
                'max:255' 
            ],
            "no_kk"=> ['required', 'max:255'],
            "tanggal_lahir"=> ['required', 'date', 'date_format:Y-m-d'],
            "tempat_lahir"=> ['required', 'max:100'],
            "no_hp_pasien"=> ['max:25'],
            "no_telp_pasien"=> ['max:20'],
            "pekerjaan"=> ['max:150'],
            "jenis_kelamin"=> ['required', 'in:L,P'],
            "kota_id"=> ['required', 'exists:kota,id'],
            "kecamatan"=> ['required', 'min:3', 'max:100'],
            "kelurahan"=> ['required', 'min:3', 'max:100'],
            "no_rw"=> ['required', 'max:3'],
            "no_rt"=> ['required', 'max:3'],
            "alamat_lengkap"=> ['required'],
            "keterangan_lain"=> ['nullable']

            // ""
        ];
    }
}
