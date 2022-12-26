<?php

namespace Kanexy\Banking\Livewire;

use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Livewire\Component;

class TransactionDetail extends Component
{
    public Transaction $transaction;

    public string $transactionType;

    protected $listeners = [
        'showTransactionDetail','refreshComponent'=>'$refresh'
    ];

    public function showTransactionDetail(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionType = (! isset($transaction->meta['card_id'])) ? 'Bank' : 'Card';
        $this->dispatchBrowserEvent('show-transaction-detail-modal');
    }

    public function render()
    {
        return view('banking::banking.livewire.transaction-detail');
    }
}
