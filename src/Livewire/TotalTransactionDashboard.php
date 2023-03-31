<?php

namespace Kanexy\Banking\Livewire;

use Illuminate\Support\Facades\Auth;
use Kanexy\Banking\Enums\BankEnum;
use Kanexy\PartnerFoundation\Core\Enums\TransactionStatus;
use Kanexy\PartnerFoundation\Core\Enums\TransactionType;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Helper;
use Livewire\Component;

class TotalTransactionDashboard extends Component
{
    public string $selectedYear, $creditTransaction, $debitTransaction;

    public $years = [];

    public function mount()
    {
        $years = Transaction::groupBy("year")->orderBy("year", "DESC")->selectRaw("YEAR(created_at) as year")->get();
        $this->years = $years->pluck("year")->toArray();
        $this->selectedYear = date('Y');
        $this->selectYear($this->selectedYear);
    }

    public function selectYear($year)
    {
        $this->selectedYear = $year;

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSubscriber()) {
            $currentWorkspaceId = app('activeWorkspaceId');
            $creditTransaction = Transaction::whereWorkspaceId($currentWorkspaceId)->whereType(TransactionType::CREDIT)->whereYear("created_at", $this->selectedYear)->selectRaw("sum(amount) as amount")->where('status', '!=', TransactionStatus::PENDING)->where('ref_type', 'wrappex')->get();
            $debitTransaction = Transaction::whereWorkspaceId($currentWorkspaceId)->whereType(TransactionType::DEBIT)->whereYear("created_at", $this->selectedYear)->selectRaw("sum(amount) as amount")->where('status', '!=', TransactionStatus::PENDING)->where('ref_type', 'wrappex')->get();
        } else {
            $creditTransaction = Transaction::whereType(TransactionType::CREDIT)->whereYear("created_at", $this->selectedYear)->selectRaw("sum(amount) as amount")->where('status', '!=', TransactionStatus::PENDING)->where('ref_type', 'wrappex')->get();
            $debitTransaction = Transaction::whereType(TransactionType::DEBIT)->whereYear("created_at", $this->selectedYear)->selectRaw("sum(amount) as amount")->where('status', '!=', TransactionStatus::PENDING)->where('ref_type', 'wrappex')->get();
        }

        $this->creditTransaction = Helper::getFormatAmount($creditTransaction->pluck("amount")[0]);

        $this->debitTransaction = Helper::getFormatAmount($debitTransaction->pluck("amount")[0]);
    }

    public function render()
    {
        return view('banking::livewire.total-transaction-dashboard');
    }
}
