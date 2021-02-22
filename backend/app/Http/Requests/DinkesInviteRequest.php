<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class DinkesInviteRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'kota_id' => 'required|exists:labkes.kota,id',
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'role_id' => [
                "required",
                "in:" . RoleEnum::DINKES()->getIndex()
            ]
        ];
    }
}
