<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'amount' => 'required|max:1050000',
            'reference' => 'required',
            'channel' => 'required|string',
            'narration' => 'required|string',
            'transaction_type' => 'required|string'
        ];
    }

    /**
     * Custom validation messages
     * 
     * @return array
     */
    public function messages(): array
    {
        return[
            'account_number.required' => 'Account number cannot be empty',
            'account_number.regex' => 'Account number is invalid',
            'amount.required' => 'Amount cannot be empty',
            'amount.max' => 'Amount permitted is exceeded',
            'reference.required' => 'Reference cannot be empty',
            'channel.required' => 'Channel cannot be empty',
            'narration.required' => 'Narration cannot be empty',
            'transaction_type.required' => 'Type of transaction cannot be empty'
        ];
    }
}
