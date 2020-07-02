<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInputHasil extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->lab_satelit_id == $this->sampel;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kesimpulan_pemeriksaan' => 'required',
            'nama_kit_pemeriksaan' => 'required',
            'hasil_deteksi.*.target_gen' => 'required',
            'hasil_deteksi.*.ct_value' => 'required',
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($this->hasil_deteksi) < 1) {        
                $validator->errors()->add("samples", 'Minimal 1 hasil deteksi CT Value');
            }
        }); 
    }
}
