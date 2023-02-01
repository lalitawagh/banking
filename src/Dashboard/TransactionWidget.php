<?php

namespace Kanexy\Banking\Dashboard;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\PartnerFoundation\Core\Helper;

class TransactionWidget extends Component
{
    public function render()
    {
        $user=Auth::user();
        $workspaceId = app('activeWorkspaceId');

        return view("banking::widget.transaction-list",compact("user", "workspaceId"));
    }
}
