<?php

namespace Kanexy\Banking\Dashboard;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\Banking\Models\Card;

class CardWidget extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $cards = Card::groupBy("status")->selectRaw("count(*) as data,upper(status) as label")->get();

            return view("banking::widget.card-chart", compact('cards'));
        }
    }
}
