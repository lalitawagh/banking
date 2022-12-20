<?php

namespace Kanexy\Banking\Services;

use Illuminate\Support\Facades\Storage;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Dtos\CreateTransactionDto;
use Kanexy\PartnerFoundation\Core\Services\WrappexService;
use Kanexy\PartnerFoundation\Cxrm\Models\Contact;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class PayoutService
{
    private WrappexService $service;

    public function __construct(WrappexService $service)
    {
        $this->service = $service;
    }

    public function initialize(Account $sender, Contact $beneficiary, array $data): Transaction
    {
        /** @var \App\Models\User|null $initiator */
        $initiator = auth()->user();
        $amount = round((float) $data['amount'], 2);

        $transaction = Transaction::create([
            'urn' => Transaction::generateUrn(),
            'amount' => $amount,
            'initiator_id' => optional($initiator)->getKey(),
            'initiator_type' => optional($initiator)->getMorphClass(),
            'workspace_id' => $sender->holder->getMorphClass() === Workspace::class ? $sender->holder->id : null,
            'type' => 'debit',
            'payment_method' => 'bank',
            'instructed_currency' => null, // TODO: Attach instructed currency.
            'instructed_amount' => $amount,
            'attachment' => !empty($data['attachment']) ? $data['attachment']->store('Images', 'azure') : null,
            'note' => $data['note'] ?? null,
            'meta' => [
                'reference' => $data['reference'] ?? null,
                'sender_id' => $sender->getKey(),
                'sender_ref_id' => $sender->ref_id,
                'sender_name' => $sender->name,
                'sender_bank_code' => $sender->bank_code,
                'sender_bank_code_type' => $sender->bank_code_type,
                'sender_bank_account_number' => $sender->account_number,
                'sender_iban_number' => $sender->iban_number,
                'sender_country_code' => $sender->country_code,
                'beneficiary_id' => $beneficiary->getKey(),
                'beneficiary_ref_id' => $beneficiary->ref_id,
                'beneficiary_avatar' => $beneficiary->avatar ? Storage::disk('azure')->url($beneficiary->avatar) : asset("dist/images/user.png"),
                'beneficiary_name' => $beneficiary->meta['bank_account_name'],
                'beneficiary_bank_code' => $beneficiary->meta['bank_code'],
                'beneficiary_bank_code_type' => $beneficiary->meta['bank_code_type'],
                'beneficiary_bank_account_number' => $beneficiary->meta['bank_account_number'],
                'beneficiary_iban_number' => $beneficiary->meta['bank_iban_number'] ?? null, // TODO: Remove null and handle it
                'beneficiary_country_code' => $beneficiary->meta['bank_country_code'] ?? null, // TODO: Remove null and handle it
                'card_id' => null,
                'card_ref_id' => null,
            ],
            'status' => 'pending-confirmation',
        ]);

        // TODO: Generate otp for confirming the transaction.

        return $transaction;
    }

    public function confirm(Transaction $transaction, string $otp): bool
    {
        return false;
    }

    public function process(Transaction $transaction)
    {
        $serviceTransaction = $this->service->createTransaction(
            new CreateTransactionDto($transaction)
        );

        $transaction->update([
            'ref_id' => $serviceTransaction['id'],
            'ref_type' => 'wrappex',
            'instructed_amount' => $serviceTransaction['meta']['instructed_amount'] ?? null,
            'instructed_currency' => $serviceTransaction['meta']['instructed_currency'] ?? null,
            'settled_amount' => $serviceTransaction['meta']['settled_amount'] ?? null,
            'settled_currency' => $serviceTransaction['meta']['settled_currency'] ?? null,
            'settlement_date' => $serviceTransaction['settlement_date'] ?? null,
            'transaction_fee' => $serviceTransaction['transaction_fee'] ?? null,
            'submitted_at' => now(),
            'reasons' => array_merge($serviceTransaction['failure_reasons'] ?? [], $serviceTransaction['rejection_reasons'] ?? []),
            'status' => $serviceTransaction['status'],
        ]);
    }
}
