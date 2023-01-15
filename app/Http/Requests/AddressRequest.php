<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'regex:/^[0-9]{7}$/'],
            'pref_id' => ['required', 'integer'],
            'address1' => ['required', 'string'],
            'address2' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'regex:/^0\d{9,10}$/'],
        ];
    }
}
