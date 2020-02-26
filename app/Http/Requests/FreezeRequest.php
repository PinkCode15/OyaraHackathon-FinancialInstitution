<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreezeRequest extends FormRequest
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
            'account_number' => 'required|regex:/^[0-9]{10}$/',
        ];
    }

    public function messages():array
    {
        return[
            'account_number.required' => 'Account number cannot be empty',
            'account_number.regex' => 'Account number is invalid',
        ];
    }
}
