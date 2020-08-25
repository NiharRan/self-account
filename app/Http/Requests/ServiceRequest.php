<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'title' => 'required',
            'amount' => 'required',
            'sector_id' => 'required',
            'payment_mode_id' => 'required'
        ];
    }


    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Service title required!',
            'amount.required' => 'Amount required!',
            'sector_id.required' => 'Sector required!',
            'payment_mode_id.required' => 'Payment mode required!',
        ];
    }
}
