<?php

namespace Kanexy\Banking\Livewire;

use Illuminate\Support\Facades\Auth;
use Kanexy\Banking\Enums\BankEnum;
use Kanexy\PartnerFoundation\Core\Enums\TransactionStatus;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Helper;
use Livewire\Component;

class TransactionListDashboard extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSubscriber()) {
            $currentWorkspaceId = Helper::activeWorkspaceId();
            $transactions = Transaction::whereWorkspaceId($currentWorkspaceId)->where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->latest()->take(15)->get();
        } else {
            $transactions = Transaction::where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->latest()->take(15)->get();
        }
        return view('banking::livewire.transaction-list-dashboard', compact('transactions'));
    }
}
