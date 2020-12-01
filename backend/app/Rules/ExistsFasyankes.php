<?php

namespace App\Rules;

use App\Models\Fasyankes;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class ExistsFasyankes implements Rule
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
        return Fasyankes::find((int)$value) ? true : false;
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
