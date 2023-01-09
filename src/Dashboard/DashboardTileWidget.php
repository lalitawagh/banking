<?php

namespace Kanexy\Banking\Dashboard;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Membership\Models\Membership;

class DashboardTileWidget extends Component
{
    private $months = [];

    public int $priority = 500;

    public function render()
    {
        foreach (range(1, 12) as $m) {
            $this->months[] = date('F', mktime(0, 0, 0, $m, 1));
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isSubscriber()) {
            $account = $user->workspaces()->first()->accounts()->first();
            $card =  ($account)?$account->cards()->first():[];
            return view("banking::widget.account-detail", compact('user', 'account', 'card'));
        } else {
            $memberships = Membership::groupBy("label")->selectRaw("count(*) as data, DATE_FORMAT(created_at,'%M') as label")->get();
            $users = $user->groupBy("status")->selectRaw("count(*) as data,upper(status) as label")->where('id','!=',1)->get();
            $totalUser = Membership::count();
            $totalCards = Card::count();
            $memberships = collect($this->months)->map(function ($month) use ($memberships) {
                $record = $memberships->where('label', $month)->first();

                if(! is_null($record)) {
                    return $record->data;
                }

                return 0;
            });

            return view("banking::widget.dashboard-tile", compact('memberships', 'users', 'totalUser', 'totalCards'));
        }
    }
}
