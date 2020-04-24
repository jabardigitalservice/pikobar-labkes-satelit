<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePengambilanRequest extends FormRequest
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
            'sampel_diambil' => ['boolean'],
            'sampel_diterima' => ['required', 'boolean'],
            'diterima_dari_faskes' => ['required', 'boolean'],
            'penerima_sampel' => ['max:150'],
            'catatan' => ['nullable'],
            'status' => ['required', 'max:50'],
            'nomor_ekstraksi' => ['max:255'],
            'sampel_rdt' => ['required', 'boolean'],

            'sampel.*.jenis_sampel'=> ['max:100'],
            'sampel.*.petugas_pengambil_sampel'=> ['max:255'],
            'sampel.*.tanggal_sampel'=> ['required', 'date', 'date_format:Y-m-d'],
            'sampel.*.waktu_sampel'=> ['date_format:H:i'],
            'sampel.*.nomor_barcode'=> ['required', 'max:255'],
            'sampel.*.nama_diluar_jenis'=> ['nullable'],
            'sampel.*.status'=> ['required', 'max:50']
        ];
    }
}
