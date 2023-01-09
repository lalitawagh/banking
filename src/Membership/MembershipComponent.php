<?php

namespace Kanexy\Banking\Membership;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kanexy\Banking\Models\Account;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\PartnerFoundation\Membership\Policies\MembershipPolicy;
use Kanexy\PartnerFoundation\Core\Helper;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class MembershipComponent extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        $this->authorize(MembershipPolicy::BANKINFO, Membership::class);

        $workspace = Workspace::findOrFail(Helper::activeWorkspaceId());
        $membership = $workspace->memberships()->first();
        $user = $workspace->users()->first();
        $account = Account::forHolder($workspace)->first();

        return view("banking::membership.membership-component", compact('workspace', 'membership', 'user', 'account'));
    }
}
