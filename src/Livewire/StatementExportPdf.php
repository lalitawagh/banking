<?php

namespace Kanexy\Banking\Livewire;

use Illuminate\Support\Facades\Auth;
use Kanexy\Banking\Models\Transaction;
use PDF;
use Livewire\Component;

class StatementExportPdf extends Component
{
    public $workspace_id;

    public $year;

    public $month=[];

    public $selectedMonth=[];

    public $selectedYear;

    protected $listeners = [
        'statementDetail',
    ];

    public function mount($workspace_id)
    {
         $this->workspace_id = $workspace_id;
    }

    public function changeMonth($value)
    {
        $this->month[] = $value;
        array_push($this->selectedMonth,$value);
    }

    public function changeYear($value)
    {
        $this->year = $value;
        $this->selectedYear = $value;
    }

    public function statementDetail()
    {

    }

    public function statementYearExport()
    {
        if(is_null($this->year))
        {
            $this->addError('year', 'The year field is required.');
        }
        else
        {
            $transactions = Transaction::whereYear('created_at', $this->year)->latest()->get();
            if(!is_null($this->workspace_id)){
                $transactions = Transaction::whereYear('created_at', $this->year)->where('workspace_id', $this->workspace_id)->latest()->get();
            }
            $account = auth()->user()->workspaces()->first()?->accounts()?->first();
            $user = Auth::user();

            $view = PDF::loadView('partner-foundation::banking.statementpdf', compact('transactions', 'account', 'user'))
                ->setPaper(array(0, 0, 1000, 800), 'landscape')
                ->output();

            return response()->streamDownload(fn () => print($view), "statement.pdf");
        }
    }

    public function statementMonthExport()
    {
        $this->validate(
            [
                'month' => 'required',
            ]
        );
        $transactions = Transaction::whereRaw('MONTH(created_at) in (' . implode(',', $this->month) . ')')->latest()->get();
        if(!is_null($this->workspace_id)){
            $transactions = Transaction::whereRaw('MONTH(created_at) in (' . implode(',', $this->month) . ')')->where('workspace_id', $this->workspace_id)->latest()->get();
        }

        $account = auth()->user()->workspaces()->first()?->accounts()->first();
        $user = Auth::user();


        $view = PDF::loadView('partner-foundation::banking.statementpdf', compact('transactions', 'account', 'user'))
            ->setPaper(array(0, 0, 1000, 800), 'landscape')
            ->output();

        return response()->streamDownload(fn () => print($view), "statement.pdf");
    }

    public function render()
    {
        $this->dispatchBrowserEvent('UpdateLivewireStatementSelect');
        return view('partner-foundation::banking.livewire.statementexport-pdf');
    }
}
