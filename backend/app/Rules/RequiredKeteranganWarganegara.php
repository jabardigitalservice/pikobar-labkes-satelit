<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredKeteranganWarganegara implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $kewarganeraan;

    public function __construct($kewarganeraan)
    {
        $this->kewarganeraan = $kewarganeraan;
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
        if ($this->kewarganeraan == 'WNA' && !$value) {
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
        return ':attribue tidak boleh kosong';
    }
}
