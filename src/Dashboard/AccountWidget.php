<?php

namespace Kanexy\Banking\Dashboard;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;

class AccountWidget extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view("banking::widget.account-detail", compact("user"));
    }
}
