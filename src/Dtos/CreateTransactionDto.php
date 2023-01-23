<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\PartnerFoundation\Core\Models\Transaction;

class CreateTransactionDto
{
    public string $beneficiary_id;

    public string $sender_ledger_account_id;

    public string $amount;

    public string $payment_medium;

    public ?string $note;

    public ?string $reference;

    public function __construct(Transaction $transaction)
    {
        $this->beneficiary_id = $transaction->meta['beneficiary_ref_id'];
        $this->sender_ledger_account_id = $transaction->meta['sender_ref_id'];
        $this->amount = $transaction->amount;
        $this->payment_medium = $transaction->payment_method;
        $this->note = $transaction->note;
        $this->reference = $transaction->meta['reference'];
    }

    public function toArray(): array
    {
        return [
            "beneficiary_id" => $this->beneficiary_id,
            "amount" => $this->amount,
            "sender_ledger_account_id" => $this->sender_ledger_account_id,
            "payment_medium" => $this->payment_medium,
            "note" => $this->note,
            "reference" => $this->reference,
        ];
    }
}
