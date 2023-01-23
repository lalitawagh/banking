<?php

namespace Kanexy\Banking\Livewire;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\I18N\Models\Country;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Banking\Models\Account;
use Kanexy\PartnerFoundation\Cxrm\Models\Contact;
use Livewire\Component;

class CloseLedgerModal extends Component
{


    public function render()
    {
        $countryWithFlags = Country::orderBy("name")->get();
        $defaultCountry = Country::find(Setting::getValue("default_country"));
        $user = Auth::user();
        $workspace = $user->workspaces()->first();
        $account = Account::Where('holder_id', $workspace->id)->first();
        if (session('contactId')) {
            $user = Contact::findOrFail(session('contactId'));
            $user->phone = $user?->mobile;
        }
        $this->dispatchBrowserEvent('close-ledger-modal');
        return view('banking::livewire.close-ledger-modal', compact("countryWithFlags", "defaultCountry", "user", "account"));
    }
}
