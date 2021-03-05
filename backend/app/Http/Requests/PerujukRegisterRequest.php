<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class PerujukRegisterRequest extends FormRequest
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
            'perujuk_id' => 'required|exists:labkes.fasyankes,id',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'name' => 'nullable',
            'password' => 'required|confirmed|min:6',
            'role_id' => [
                "required",
                "in:" . RoleEnum::PERUJUK()->getIndex()
            ]
        ];
    }
}
