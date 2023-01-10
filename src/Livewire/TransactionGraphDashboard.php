<?php

namespace Kanexy\Banking\Livewire;

use Illuminate\Support\Facades\Auth;
use Kanexy\PartnerFoundation\Banking\Enums\BankEnum;
use Kanexy\PartnerFoundation\Core\Enums\TransactionStatus;
use Kanexy\PartnerFoundation\Core\Enums\TransactionType;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Helper;
use Livewire\Component;

class TransactionGraphDashboard extends Component
{
    private $months = [];

    public $selectedYear, $creditTransactionGraphData, $debitTransactionGraphData;

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

        foreach (range(1, 12) as $m) {
            $this->months[] = date('F', mktime(0, 0, 0, $m, 1));
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSubscriber()) {
            $currentWorkspaceId = Helper::activeWorkspaceId();
            $creditTransactionGraphData = Transaction::whereWorkspaceId($currentWorkspaceId)->whereType(TransactionType::CREDIT)->whereYear("created_at", $this->selectedYear)->groupBy(["label"])->selectRaw("ROUND(sum(amount),2) as data, MONTHNAME(created_at) as label")->where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->get();
            $debitTransactionGraphData = Transaction::whereWorkspaceId($currentWorkspaceId)->whereType(TransactionType::DEBIT)->whereYear("created_at", $this->selectedYear)->groupBy(["label"])->selectRaw("ROUND(sum(amount),2) as data, MONTHNAME(created_at) as label")->where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->get();

        } else {
            $creditTransactionGraphData = Transaction::whereType(TransactionType::CREDIT)->whereYear("created_at", $this->selectedYear)->groupBy(["label"])->selectRaw("ROUND(sum(amount),2) as data, MONTHNAME(created_at) as label")->where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->get();
            $debitTransactionGraphData = Transaction::whereType(TransactionType::DEBIT)->whereYear("created_at", $this->selectedYear)->groupBy(["label"])->selectRaw("ROUND(sum(amount),2) as data, MONTHNAME(created_at) as label")->where('status', '!=', TransactionStatus::PENDING_CONFIRMATION)->where('ref_type', 'wrappex')->get();
        }

        $creditTransactionGraphData = collect($this->months)->map(function ($month) use ($creditTransactionGraphData) {
            $record = $creditTransactionGraphData->where('label', $month)->first();

            if (!is_null($record)) {
                return $record->data;
            }

            return 0;
        });

        $debitTransactionGraphData = collect($this->months)->map(function ($month) use ($debitTransactionGraphData) {
            $record = $debitTransactionGraphData->where('label', $month)->first();

            if (!is_null($record)) {
                return $record->data;
            }

            return 0;
        });

        $this->creditTransactionGraphData = $creditTransactionGraphData;

        $this->debitTransactionGraphData = $debitTransactionGraphData;

        $this->dispatchBrowserEvent('UpdateTransactionChart');
    }

    public function render()
    {
        return view('banking::livewire.transaction-graph-dashboard');
    }
}
