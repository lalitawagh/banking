<?php

namespace Kanexy\Banking\Step;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Enums\RegistrationStep;
use Kanexy\Cms\Enums\Role;
use Kanexy\Cms\Step\Contracts\Item;
use Kanexy\Cms\Step\StepItem;

class PersonalBankingRegistrationStep extends Item
{
    protected string $type = 'customers';

    protected string $flow = 'personal_banking_account';

    protected string $role = Role::SUBSCRIBER;

    public int $priority = 7000;

    public function getItems(): array
    {
        $user = Auth::user();

        $steps = [
            new StepItem(membership_type: 'personal', label: 'Plan & Packages', step: RegistrationStep::PLAN_AND_PACKAGES ,source :'web', url: route('customer.signup.plan.index'),priority:4000),
            new StepItem(membership_type: 'personal', label: 'Services', step: RegistrationStep::SERVICE_NEEDED ,source :'web', url: route('customer.signup.services.index'),priority:5000),
            new StepItem(membership_type: 'personal', label: 'Banking', step: RegistrationStep::BANKING ,source :'web', url: route('customer.signup.banking.index'),priority:6000),
            new StepItem(membership_type: 'personal', label: 'Finance Checks', step: RegistrationStep::FINANCE_CHECKS ,source :'web', url: route('customer.signup.finance-checks.index'),priority:7000),
            new StepItem(membership_type: 'personal', label: 'Address & DOB', step: RegistrationStep::ADDRESS ,source :'web', url: route('customer.signup.address.index'),priority:8000),
            new StepItem(membership_type: 'personal', label: 'KYC Documents', step: RegistrationStep::DOCUMENTS ,source :'web', url: route('customer.signup.kyc.index'),priority:9000),

        ];

        $steps[] = new StepItem(membership_type: 'personal', label: 'Account Preview', step: RegistrationStep::ACCOUNT_PREVIEW ,source :'web', url: route('customer.signup.ledger.index'),priority:10000);
        $steps[] = new StepItem(membership_type: 'personal', label: 'Ledger', step: RegistrationStep::LEDGER ,source :'web', url: route('customer.signup.ledger-show'),priority:11000);

        if($user->is_banking_user != true)
        {
            $steps[] =  new StepItem(membership_type: 'personal', label: 'Dashboard', step: RegistrationStep::DASHBOARD ,source :'web', url: route('dashboard.index'),priority:12000);
        }

        return $steps;
    }
}

