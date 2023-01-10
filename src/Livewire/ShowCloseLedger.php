<?php

namespace Kanexy\Banking\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\I18N\Models\Country;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\PartnerFoundation\Core\Models\ArchivedMember;
use Livewire\Component;

class ShowCloseLedger extends Component
{
    public ArchivedMember $details;
    public $country_id;

    protected $listeners = ['show', 'refreshComponent' => '$refresh'];

    public function show(ArchivedMember $details)
    {
        $this->details = $details;
        $user = User::find($details->holder_id);
        $this->country_id = $user->country_id;
    }

    public function render()
    {
        $countryWithFlags = Country::orderBy("name")->get();
        return view('banking::livewire.show-close-ledger', compact("countryWithFlags"));
    }
}
