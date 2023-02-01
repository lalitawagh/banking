<?php

namespace Kanexy\Banking\Step;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Enums\RegistrationStep;
use Kanexy\Cms\Enums\Role;
use Kanexy\Cms\Step\Contracts\Item;
use Kanexy\Cms\Step\StepItem;
use Kanexy\PartnerFoundation\Core\Helper;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class BusinessBankingRegistrationStep extends Item
{
    protected string $type = 'customers';

    protected string $flow = 'business_banking_account';

    protected string $role = Role::SUBSCRIBER;

    public int $priority = 6000;

    public function getItems(): array
    {
        $user = Auth::user();
        $workspace = $user->workspaces()->first();
        if(is_null($workspace))
        {
            $workspace = Workspace::find(app('activeWorkspaceId'));
        }

        $steps = [
            new StepItem(membership_type: 'business', label: 'Plan & Packages', step: RegistrationStep::PLAN_AND_PACKAGES ,source :'web', url: route('customer.signup.plan.index'),priority:3000),
            new StepItem(membership_type: 'business', label: 'Services', step: RegistrationStep::SERVICE_NEEDED ,source :'web', url: route('customer.signup.services.index'),priority:4000),
            new StepItem(membership_type: 'business', label: 'Banking', step: RegistrationStep::BANKING ,source :'web', url: route('customer.signup.banking.index'),priority:5000),
            new StepItem(membership_type: 'business', label: 'Address & DOB', step: RegistrationStep::ADDRESS ,source :'web', url: route('customer.signup.address.index'),priority:6000),
            new StepItem(membership_type: 'business', label: 'KYC Documents', step: RegistrationStep::DOCUMENTS ,source :'web', url: route('customer.signup.kyc.index'),priority:7000),
            new StepItem(membership_type: 'business', label: 'Company Details', step: RegistrationStep::COMPANY_REGISTRATION ,source :'web', url: route('customer.signup.company-registration.index'),priority:8000),
            new StepItem(membership_type: 'business', label: 'Additional Information', step: RegistrationStep::ADDITIONAL_INFORMATION ,source :'web', url: route('customer.signup.additional-information.index'),priority:9000),
            new StepItem(membership_type: 'business', label: 'Company Address', step: RegistrationStep::COMPANY_ADDRESS ,source :'web', url: route('customer.signup.company-address.index'),priority:10000),
            new StepItem(membership_type: 'business', label: 'Company Officers', step: RegistrationStep::COMPANY_OFFICERS ,source :'web', url: route('customer.signup.company-officers.index'),priority:11000),
        ];

        if($workspace->is_registered != 1)
        {
            $steps[] = new StepItem(membership_type: 'business', label: 'KYB Documents', step: RegistrationStep::COMPANY_DOCUMENTS ,source :'web', url: route('customer.signup.company-documents.index'),priority:12000);
        }

        $steps[] = new StepItem(membership_type: 'business', label: 'Confirmation', step: RegistrationStep::ACCOUNT_PREVIEW ,source :'web', url: route('customer.signup.ledger.index'),priority:13000);
        $steps[] = new StepItem(membership_type: 'business', label: 'Preview Ledger', step: RegistrationStep::LEDGER ,source :'web', url: route('customer.signup.ledger-show'),priority:14000);

        return $steps;
    }
}

