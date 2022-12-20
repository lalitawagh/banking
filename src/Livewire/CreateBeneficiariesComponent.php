<?php

namespace Kanexy\Banking\Livewire;


use Illuminate\Validation\Rule;
use Kanexy\Cms\Helper;
use Kanexy\Cms\Rules\AlphaSpaces;
use Kanexy\Cms\Rules\LandlineNumber;
use Kanexy\Cms\Rules\MobileNumber;
use Kanexy\Banking\Enums\BankEnum;
use Kanexy\Banking\Enums\ContactBeneficiaryType;
use Kanexy\Banking\Enums\ContactClassificationType;
use Kanexy\Banking\Enums\ContactType;
use Kanexy\PartnerFoundation\Core\Rules\BeneficiaryUnique;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Livewire\Component;
use Livewire\WithFileUploads;
use Kanexy\PartnerFoundation\Core\Services\WrappexService;
use Kanexy\PartnerFoundation\Core\Dtos\CreateBeneficiaryDto;
use Kanexy\PartnerFoundation\Cxrm\Models\Contact;
use Kanexy\PartnerFoundation\Cxrm\Events\ContactCreated;
use Kanexy\Cms\Notifications\SmsOneTimePasswordNotification;


class CreateBeneficiariesComponent extends Component
{

    use WithFileUploads;

    public $oneTimePassword;
    public $countries;
    public $defaultCountry;
    public $workspace;
    public $beneficiary_created;
    public $workspace_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $company_name;
    public $email;
    public $landline;
    public $mobile;
    public $type = 'personal';
    public $is_partner_account;
    public $avatar;
    public $note;
    public $classification;
    public $meta = [
        "bank_account_number" => '',
        "bank_code" => '',
        "bank_code_type" => '',
        "bank_account_name" => '',
        "bank_country" => '',
        "beneficiary_type" => '',
    ];

    /*
    * commented for later use un-comment these lines for instant validation
    *
    */
    public function updated($propertyName)
    {
        $this->dispatchBrowserEvent('UpdateLivewireSelect');
    }

    public function mount($workspaceId, $countries, $defaultCountry)
    {

        $this->countries = $countries;

        $this->defaultCountry = $defaultCountry;

        $this->meta['bank_country'] = $this->defaultCountry;
        if ($workspaceId) {
            $this->workspace = Workspace::findOrFail($workspaceId);
        }
        $this->classification = 'beneficiary';
        $this->workspace_id = $this->workspace->id;
        $this->meta['bank_code_type'] = 'sort-code';
    }

    public function rules()
    {
        return [
            'workspace_id' => "required|exists:workspaces,id",
            'first_name' => ['required_if:type,' . ContactType::PERSONAL, 'nullable', new AlphaSpaces, 'string'],
            'middle_name' => ['nullable', new AlphaSpaces, 'string'],
            'last_name' => ['required_if:type,' . ContactType::PERSONAL, 'nullable', new AlphaSpaces, 'string'],
            'company_name' => ['required_if:type,' . ContactType::COMPANY, 'nullable', new AlphaSpaces, 'string'],
            'email' => ['nullable', 'email'],
            'landline' => ['nullable', 'string', new LandlineNumber],
            'mobile' => ['nullable', new MobileNumber],
            'type' => ['required', 'string', Rule::in(ContactType::toArray())],
            'is_partner_account' => ['nullable'],
            'avatar' => ['nullable', 'max:5120', 'mimes:png,jpg,jpeg', 'file'],
            'note' => ['nullable'],
            'classification' => ['required', 'string', Rule::in(ContactClassificationType::toArray())],
            'meta' => ['required', 'array'],
            'meta.bank_account_number' => ['required', 'string', 'numeric', 'digits:8', new BeneficiaryUnique($this->meta['bank_code'], $this->workspace_id)],
            'meta.bank_code' => ['required', 'string', 'numeric', 'digits:6'],
            'meta.bank_code_type' => ['required', 'string', Rule::in([BankEnum::SORTCODE])],
            'meta.bank_account_name' => ['required', new AlphaSpaces, 'string'],
            'meta.bank_country' => ['required', 'exists:countries,id'],
            'meta.beneficiary_type' => ['nullable', 'array', Rule::in(ContactBeneficiaryType::toArray())],
        ];
    }

    protected $messages = [
        'avatar.max' => 'File size should not exceed 5MB.'
    ];


    protected $validationAttributes = [
        'meta.bank_account_number' => 'bank account number',
        'meta.bank_code' => 'bank sort code',
        'meta.bank_account_name' => 'bank account name',
        'meta.bank_country' => 'bank country',
    ];

    public function createBenificiary()
    {

        $validatedData = $this->validate();

        $workspace = Workspace::findOrFail($this->workspace_id);

        if ($validatedData['type'] == 'company') {
            $validatedData['display_name'] = Helper::removeExtraSpace($validatedData['company_name']);
        } else {
            $validatedData['display_name'] = Helper::removeExtraSpace(implode(' ', [$validatedData['first_name'], $validatedData['middle_name'], $validatedData['last_name']]));
        }

        if ($this->avatar) {

            $validatedData['avatar'] = $this->avatar->store('Images', 'azure');
        }


        $service = new WrappexService();

        $beneficiaryRefId = $service->createBeneficiary(
            new CreateBeneficiaryDto($workspace->ref_id, $validatedData)
        );

        $validatedData['workspace_id'] = $workspace->id;
        $validatedData['ref_id']       = $beneficiaryRefId;
        $validatedData['ref_type']     = 'wrappex';

        /** @var Contact $contact */
        $contact = Contact::create($validatedData);

        event(new ContactCreated($contact));

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $otpInfo = $contact->generateOtp("sms");

        if(config('services.disable_sms_service') == false){
            $user->notify(new SmsOneTimePasswordNotification($otpInfo));
        }


        $this->oneTimePassword = $contact->oneTimePasswords()->first()?->id;

        session(['user' => $user, 'contact' => $contact, 'oneTimePassword' => $this->oneTimePassword, 'workspaceId' => $this->workspace_id]);

        if ($contact->hasActiveOneTimePassword('sms')) {
            $this->beneficiary_created = true;
            $this->OpenOtpVerificationOverlay();
        }
    }

    public function OpenOtpVerificationOverlay()
    {
        $this->dispatchBrowserEvent('openOverLay', ['overlayName' => 'otp-verification-btn']);
    }

    public function render()
    {

        $this->dispatchBrowserEvent('setBeneficiaryType',['type' => $this->type]);

        return view('partner-foundation::banking.livewire.create-beneficiaries');
    }
}
