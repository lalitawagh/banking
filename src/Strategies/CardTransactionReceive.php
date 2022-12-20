<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Card;
use Kanexy\Banking\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardTransactionReceive implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['account_id']);

        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
            $transaction->updateStatus($payload['transaction_status']);

        } catch (\Exception $exception) {
            $transaction = Transaction::findOrFailByRef($payload['meta']['original_transaction_ref_id']);
            $transaction->update(['status' => 'reversed']);
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
        }

    }

    private function createTransactionForAccountFromPayload(Account $account, array $payload)
    {
        $card = Card::findOrFailByRef($payload['meta']['card_id']);

        return Transaction::create([
            'urn' => Transaction::generateUrn(),
            'amount' => $payload['amount'],
            'workspace_id' => $account->holder_id,
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
                'original_transaction_id' => $payload['meta']['original_transaction_id'] ?? null,
                'reference' => $payload['reference'] ?? null,
                'sender_id' => $account->getKey(),
                'sender_ref_id' => $account->ref_id,
                'sender_name' => $account->name,
                'sender_bank_code' => $account->bank_code,
                'sender_bank_code_type' => $account->bank_code_type,
                'sender_bank_account_number' => $account->account_number,
                'sender_iban_number' => $account->iban_number,
                'sender_country_code' => $account->country_code,
                'beneficiary_id' => $payload['meta']['beneficiary_id'],
                'beneficiary_ref_id' => $payload['meta']['beneficiary_ref_id'],
                'beneficiary_name' => $payload['meta']['beneficiary_name'],
                'beneficiary_bank_code' => $payload['meta']['beneficiary_bank_code'],
                'beneficiary_bank_code_type' => $payload['meta']['beneficiary_bank_code_type'],
                'beneficiary_bank_account_number' => $payload['meta']['beneficiary_bank_account_number'],
                'beneficiary_iban_number' => $payload['meta']['beneficiary_iban_number'],
                'beneficiary_country_code' => $payload['meta']['beneficiary_country_code'],
                'card_id' => $card->id,
                'card_ref_id' => $card->ref_id,
            ],
        ]);
    }
}
