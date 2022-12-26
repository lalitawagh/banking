<?php

namespace Kanexy\Banking\Registration;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;

class BankingServiceSelectionContent extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view("banking::registration.banking-component", compact("user"));
    }
}
