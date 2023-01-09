<?php

namespace Kanexy\Banking\Strategies;

use Exception;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardTransaction implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['account_id']);

        $statusHandler = "handle" . ucfirst($payload['status']) . "Transaction";

        if (! method_exists($this, $statusHandler)) {
            throw new Exception("No handler exists for [" . $payload['status'] . "].");
        }

        $this->$statusHandler($account, $payload);
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

    private function handlePendingTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
            // $account->updateBalance($transaction);
        }

        $transaction->update(['status' => $payload['status']]);
    }

    private function handleQuarantineTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
            // $account->updateBalance($transaction);
        }

        $transaction->update(['status' => $payload['status']]);
    }

    private function handleApprovedTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
            // $account->updateBalance($transaction);
        }

        $transaction->update(['status' => $payload['status']]);
    }

    private function handleAcceptedTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
            // $account->updateBalance($transaction);
        }

        $transaction->update(['status' => $payload['status']]);
    }

    private function handleReversedTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
        }

        $transaction->update(['status' => $payload['status']]);
    }

    private function handleDeclinedTransaction(Account $account, array $payload)
    {
        try {
            /** @var Transaction $transaction */
            $transaction = Transaction::findOrFailByRef($payload['id']);
            $account->creditAmount($transaction);
        } catch (\Exception $exception) {
            $transaction = $this->createTransactionForAccountFromPayload($account, $payload);
        }

        $transaction->update(['status' => $payload['status']]);
    }
}
