<?php

namespace App\Rules;

use App\Enums\JenisSampelEnum;
use Illuminate\Contracts\Validation\Rule;

class RequiredNamaJenisSampel implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $jenis_sampel;

    public function __construct($jenis_sampel)
    {
        $this->jenis_sampel = $jenis_sampel;
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
        if ($this->jenis_sampel == JenisSampelEnum::LAINNYA()->getIndex() && !$value) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute tidak boleh kosong';
    }
}
