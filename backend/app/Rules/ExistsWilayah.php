<?php

namespace App\Rules;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class ExistsWilayah implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = getConvertCodeDagri($value);
        switch ($attribute) {
            case 'kode_provinsi':
                $models = Provinsi::find($value);
                break;
            case 'kode_kotakab':
                $models = Kota::find($value);
                break;
            case 'kode_kecamatan':
                $models = Kecamatan::find($value);
                break;
            case 'kode_kelurahan':
                $models = Kelurahan::find($value);
                break;
        }
        return $models ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('validation.exists');
    }
}
