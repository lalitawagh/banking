<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Account;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class LedgerChanged implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        /** @var Account $account */
        $account = Account::findOrFailByRef($payload['id']);

        $account->update([
            'balance' => $payload['amount'],
        ]);
    }
}
