<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Account;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class TransactionDeclined implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        if ($payload['type'] === 'debit') {
            $this->handleTransactionDebit($payload);
        } else {
            $this->handleTransactionCredit($payload);
        }
    }

    private function handleTransactionCredit(array $payload)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['account_id']);

        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
            $transaction->update(['reasons' => $payload['failure_reasons'], 'status' => $payload['status']]);
        } catch (\Exception $exception) {
           $transaction = $this->createTransactionForAccountFromPayload($account,$payload);
        }
    }

    private function handleTransactionDebit(array $payload)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['account_id']);

        /** @var Transaction $transaction */
        $transaction = Transaction::findOrFailByRef($payload['id']);
        $transaction->update([
            'reasons' => $payload['failure_reasons'],
            'status' => $payload['status'],
        ]);
    }

    private function createTransactionForAccountFromPayload(Account $beneficiary, array $payload)
    {
        return Transaction::create([
            'urn' => Transaction::generateUrn(),
            'amount' => $payload['amount'],
            'workspace_id' => $beneficiary->holder_id,
            'type' => $payload['type'],
            'payment_method' => $payload['payment_method'],
            'instructed_amount' => $payload['meta']['instructed_amount'],
            'instructed_currency' => $payload['meta']['instructed_currency'],
            'note' => null,
            'ref_id' => $payload['id'],
            'ref_type' => 'wrappex',
            'settled_amount' => $payload['meta']['settled_amount'],
            'settled_currency' => $payload['meta']['settled_currency'],
            'settlement_date' => $payload['settlement_date'],
            'settled_at' => now(),
            'transaction_fee' => $payload['transaction_fee'],
            'reasons' => $payload['failure_reasons'],
            'submitted_at' => $payload['submitted_at'],
            'status' => $payload['status'],
            'meta' => [
                'reference' => $payload['reference'],
                'sender_id' => null,
                'sender_ref_id' => $payload['meta']['sender_id'],
                'sender_name' => $payload['meta']['sender_name'],
                'sender_bank_code' => $payload['meta']['sender_bank_code'],
                'sender_bank_code_type' => $payload['meta']['sender_bank_code_type'],
                'sender_bank_account_number' => $payload['meta']['sender_bank_account_number'],
                'sender_iban_number' => $payload['meta']['sender_iban_number'],
                'sender_country_code' => $payload['meta']['sender_country_code'],
                'beneficiary_id' => $beneficiary->id,
                'beneficiary_ref_id' => $payload['meta']['beneficiary_id'],
                'beneficiary_name' => $payload['meta']['beneficiary_name'],
                'beneficiary_bank_code' => $beneficiary->bank_code,
                'beneficiary_bank_code_type' => $beneficiary->bank_code_type,
                'beneficiary_bank_account_number' => $beneficiary->account_number,
                'beneficiary_iban_number' => $beneficiary->iban_number,
                'beneficiary_country_code' => $beneficiary->country_code,
                'card_id' => null,
                'card_ref_id' => null,
            ],
        ]);
    }
}
