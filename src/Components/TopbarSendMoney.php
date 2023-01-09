<?php

namespace Kanexy\Banking\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\PartnerFoundation\Core\Helper;

class TopbarSendMoney extends Component
{
    public int $priority = 1000;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function render()
    {
        $workspaces = Auth::user()->workspaces;

        $activeWorkspaceId = null;
        if ($workspaces->count() > 0) {
            $activeWorkspaceId = Helper::activeWorkspaceId();
        }

        return view("banking::banking.components.topbar-send-money", compact("activeWorkspaceId"));
    }
}
