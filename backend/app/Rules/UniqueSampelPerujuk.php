<?php

namespace App\Rules;

use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class UniqueSampelPerujuk implements Rule
{

    public $id;
    public $lab_satelit_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lab_satelit_id, $id = null)
    {
        $this->id = $id;
        $this->lab_satelit_id = $lab_satelit_id;
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

        $sampel = Sampel::where('nomor_sampel', strtoupper($value))
                ->where('lab_satelit_id', $this->lab_satelit_id)
                ->doesntExist();
        $registerPerujuk = RegisterPerujuk::where('nomor_sampel', strtoupper($value))
            ->where('lab_satelit_id', $this->lab_satelit_id)
            ->where(function ($query) {
                if ($this->id) {
                    $query->where('id', '!=', $this->id);
                }
            })
            ->doesntExist();
        $result = $this->id ? $registerPerujuk : $sampel && $registerPerujuk;
        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('validation.unique');
    }
}
