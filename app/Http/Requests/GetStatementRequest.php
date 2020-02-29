<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetStatementRequest extends FormRequest
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
            'channel' => 'string|required',
            'account_number' => 'required|regex:/^[0-9]{10}$/',
            'reference' => 'string|required',
            'start_date' => 'date_format:Y-m-d|required',
            'end_date' => 'date_format:Y-m-d|required'
        ];
    }

    public function messages():array
    {
        return[
            'account_number.required' => 'Account number cannot be empty',
            'account_number.regex' => 'Account number is invalid',
            'channel.string' => 'Invalid format',
            'reference.string' => 'Invalid format',
            'start_date.date_format' => 'Invalid format. Date should be YYYY-MM-DD',
            'end_date.date_format' => 'Invalid format. Date should be YYYY-MM-DD',
            'channel.required' => 'Channel cannot be empty',
            'reference.required' => 'Reference cannot be empty',
            'start_date.required' => 'Start date cannot be empty',
            'end_date.required' => 'End date cannot be empty',
        ];
    }
}
