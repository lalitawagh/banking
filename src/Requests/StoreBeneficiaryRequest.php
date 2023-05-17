<?php

namespace Kanexy\Banking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Kanexy\Cms\Rules\AlphaSpaces;
use Kanexy\Cms\Rules\Email;
use Kanexy\Cms\Rules\LandlineNumber;
use Kanexy\Cms\Rules\MobileNumber;
use Kanexy\Banking\Enums\BankEnum;
use Kanexy\PartnerFoundation\Cxrm\Enums\ContactBeneficiaryType;
use Kanexy\PartnerFoundation\Cxrm\Enums\ContactClassificationType;
use Kanexy\PartnerFoundation\Cxrm\Enums\ContactType;
use Kanexy\PartnerFoundation\Core\Rules\BeneficiaryUnique;
use Kanexy\PartnerFoundation\Cxrm\Models\Contact;
use Kanexy\PartnerFoundation\Cxrm\Policies\ContactPolicy;

class StoreBeneficiaryRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can(ContactPolicy::CREATE, Contact::class);
    }

    public function rules()
    {
        return [
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'first_name' => ['required_if:type,' . ContactType::PERSONAL, 'nullable', new AlphaSpaces, 'string', 'max:40'],
            'middle_name' => ['nullable', new AlphaSpaces, 'string', 'max:40'],
            'last_name' => ['nullable', new AlphaSpaces, 'string', 'max:40'],
            'company_name' => ['required_if:type,' . ContactType::COMPANY, 'nullable', new AlphaSpaces, 'string'],
            'email' => ['nullable', 'email'],
            'landline' => ['nullable', 'string', new LandlineNumber],
            'mobile' => ['nullable', new MobileNumber],
            'type' => ['required', 'string', Rule::in(ContactType::toArray())],
            'is_partner_account' => ['nullable'],
            'avatar' => ['nullable', 'max:5120', 'mimes:png,jpg,jpeg', 'file'],
            'note' => ['nullable'],
            'classification' => ['nullable', 'array'],
            'classification.*' => ['required', 'string', Rule::in(ContactClassificationType::toArray())],
            'meta' => ['required', 'array'],
            'meta.bank_account_number' => ['required', 'string', 'numeric', 'digits:8', new BeneficiaryUnique($this->input('meta.bank_code'), $this->input('workspace_id'))],
            'meta.bank_code' => ['required', 'string', 'numeric', 'digits:6'],
            'meta.bank_code_type' => ['required', 'string', Rule::in([BankEnum::SORTCODE])],
            'meta.bank_account_name' => ['required', 'string', new AlphaSpaces],
            'meta.bank_country' => ['required', 'exists:countries,id'],
            'meta.beneficiary_type' => ['nullable', 'array', Rule::in(ContactBeneficiaryType::toArray())],
        ];
    }

    public function messages()
    {
        return [
            'avatar.max' => 'File size should not exceed 5MB.',
        ];
    }

    public function attributes()
    {
        return [
            'meta.bank_account_number' => 'bank account number',
            'meta.bank_code' => 'bank sort code',
            'meta.bank_account_name' => 'bank account name',
            'meta.bank_country' => 'bank country',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bankCodeType = $this->input('meta.bank_code_type');

            if (!empty($bankCodeType) && $bankCodeType === 'sort-code') {
                if (strlen($this->input('meta.bank_code')) !== 6) {
                    $validator->errors()->add('meta.bank_code', 'The bank sort code must be 6 characters long.');
                }
            }
        });
    }
}
