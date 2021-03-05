<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabInviteRequest extends FormRequest
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
            'lab_satelit_id' => 'required|exists:lab_satelit,id',
        ];
    }
}
