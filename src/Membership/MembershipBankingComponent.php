<?php

namespace Kanexy\Banking\Membership;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kanexy\Banking\Models\Account;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class MembershipBankingComponent extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        $workspace = Workspace::find(request()->route('workspace'));
        $membership = $workspace?->memberships()->first();
        $user = $workspace?->users()->first();
        $account = Account::forHolder($workspace)->first();

        return view("banking::membership.membership-banking-component", compact('workspace', 'membership', 'user', 'account'));
    }
}
