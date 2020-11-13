<?php

namespace App\Rules;

use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use Illuminate\Contracts\Validation\Rule;

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
        $result = false;
        if (!$this->id) {
            $sampel = Sampel::where('nomor_sampel', strtoupper($value))
                ->where('lab_satelit_id', $this->lab_satelit_id)
                ->first();
            $result = $sampel ? false : true;
            if ($result) {
                $sampel = RegisterPerujuk::where('nomor_sampel', strtoupper($value))
                    ->where('lab_satelit_id', $this->lab_satelit_id)
                    ->first();
                $result = $sampel ? false : true;
            }
        } else {
            $sampel = RegisterPerujuk::where('nomor_sampel', strtoupper($value))
                ->where('lab_satelit_id', $this->lab_satelit_id)
                ->first();
            $result = $sampel && $sampel->id == $this->id ? true : false;
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute sudah digunakan';
    }
}
