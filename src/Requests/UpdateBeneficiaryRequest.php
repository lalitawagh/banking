<?php

namespace Kanexy\Banking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Kanexy\Cms\Rules\AlphaSpaces;
use Kanexy\Cms\Rules\LandlineNumber;
use Kanexy\Cms\Rules\MobileNumber;
use Kanexy\PartnerFoundation\Cxrm\Enums\ContactType;
use Kanexy\PartnerFoundation\Cxrm\Policies\ContactPolicy;

class UpdateBeneficiaryRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can(ContactPolicy::EDIT, $this->route('beneficiary'));
    }

    public function rules()
    {
        return [
            'first_name' => ['required_if:type,' . ContactType::PERSONAL, 'nullable', new AlphaSpaces, 'string'],
            'middle_name' => ['nullable', new AlphaSpaces, 'string'],
            'last_name' => ['nullable', new AlphaSpaces, 'string'],
            'company_name' => ['required_if:type,' . ContactType::COMPANY, 'nullable', new AlphaSpaces, 'string'],
            'email' => ['nullable', 'email'],
            'landline' => ['nullable', 'string', new LandlineNumber],
            'mobile' => ['nullable', new MobileNumber],
            'type' => ['required', 'string', Rule::in(ContactType::toArray())],
            'avatar' => ['nullable', 'max:5120', 'mimes:png,jpg,jpeg', 'file'],
        ];
    }

    public function messages()
    {
        return [
            'avatar.max' => 'File size should not exceed 5MB.',
        ];
    }
}
