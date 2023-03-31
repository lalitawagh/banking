<?php

namespace Kanexy\Banking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardAddressRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'billing_address_id' => ['nullable', 'string', 'exists:addresses,id'],
            'shipping_address_id' => ['nullable', 'string', 'exists:addresses,id'],

        ];
    }

    public function messages()
    {
        return [
            'billing_address_id.required' => 'Billing address field is required',
            'shipping_address_id.required' => 'Shipping address field is required',
        ];
    }
}
