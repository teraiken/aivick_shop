<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address;

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

    protected function prepareForValidation()
    {
        if ($this->address == 'registeredAddress') {
            $address = Address::find($this->address_id);

            $data = [];
            $data['name'] = $address->name;
            $data['postal_code'] = $address->postal_code;
            $data['pref_id'] = $address->pref_id;
            $data['address1'] = $address->address1;
            $data['address2'] = $address->address2;
            $data['phone_number'] = $address->phone_number;

            $this->merge($data);
        }
    }
}
