<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'account_name' => 'required|string',
            'currency' => 'required|string|max:3',
            'account_type' => 'required|string',
            'email' => 'string|email|max:255',
            'bvn' => 'required|regex:/^[0-9]{10}$/',
            'full_name' => 'required|string',
            'phone_number' => 'regex:/^[0-9]{11}$/',
        ];
    }

    public function messages():array
    {
        return[
            'account_number.required' => 'Account number cannot be empty',
            'account_number.regex' => 'Account number is invalid',
            'account_name.required' => 'Account name cannot be empty',
            'currency.required' => 'Currency cannot be empty',
            'currency.max' => 'Currency is invalid e.g NGN, USD',
            'account_type.required' => 'Account type cannot be empty',
            'bvn.required' => 'Currency cannot be empty',
            'bvn.regex' => 'BVN is invalid',
            'full_name.required' => 'Full name cannot be empty'
        ];
    }
}
