<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Account;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class AccountReadyToUse implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['id']);

        $account->update([
            'account_number' => $payload['uk_account_number'],
            'bank_code' => $payload['uk_sort_code'],
            'iban_number' => $payload['iban'],
            'bic_swift' => $payload['bic_swift'],
            'iban_status' => $payload['iban_status'],
            'balance' => $payload['amount'],
            'status' => $payload['status'] === 'active' ? 'active' : 'inactive',
        ]);
    }
}
