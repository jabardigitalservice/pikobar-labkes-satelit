<?php

namespace App\Rules;

use App\Enums\RoleEnum;
use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class UniqueSampelPerujuk implements Rule
{

    public $id;
    public $lab_satelit_id;
    private $roleLab;
    private $rolePerujuk;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lab_satelit_id, $id = null)
    {
        $this->id = $id;
        $this->lab_satelit_id = $lab_satelit_id;
        $this->roleLab = RoleEnum::LABORATORIUM()->getIndex();
        $this->rolePerujuk = RoleEnum::PERUJUK()->getIndex();
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
        return $this->doestntExistSampel($value) &&
                $this->doestntExistRegistrasiPerujuk($value);
    }

    private function doestntExistSampel($nomor_sampel)
    {
        return Sampel::where('nomor_sampel', strtoupper($nomor_sampel))
                ->where('lab_satelit_id', $this->lab_satelit_id)
                ->where(function ($query) {
                    if ($this->id && $this->roleLab == Auth::user()->role_id) {
                        $query->where('id', '!=', $this->id);
                    }
                })
                ->doesntExist();
    }

    private function doestntExistRegistrasiPerujuk($nomor_sampel)
    {
        return RegisterPerujuk::where('nomor_sampel', strtoupper($nomor_sampel))
                ->where('lab_satelit_id', $this->lab_satelit_id)
                ->where(function ($query) {
                    if ($this->id && $this->rolePerujuk == Auth::user()->role_id) {
                        $query->where('id', '!=', $this->id);
                    }
                })
                ->doesntExist();
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
