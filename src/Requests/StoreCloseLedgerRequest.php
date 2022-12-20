<?php

namespace Kanexy\Banking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kanexy\Banking\Policies\CloseLedgerPolicy;
use Kanexy\PartnerFoundation\Core\Models\ArchivedMember;

class StoreCloseLedgerRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can(CloseLedgerPolicy::CREATE, ArchivedMember::class);
    }

    public function rules()
    {
        return [
            'email' => ['required'],
            'country_code' => ['required'],
            'phone' => ['required'],
            'name' => ['required'],
            'bank_code' => ['required'],
            'account_number' => ['required'],
            'iban_number' => ['required'],
            'bic_swift' => ['required'],
            'reason' => ['required']
        ];
    }
}
