<?php

namespace App\Http\Requests;

use App\Rules\ExcelExtensionRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportValidationRequest extends FormRequest
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
            'register_file' => [
                'required',
                'file',
                'max:2048',
                new ExcelExtensionRule($this->register_file->getClientOriginalExtension())
            ]
        ];
    }
}
