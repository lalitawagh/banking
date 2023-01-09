<?php

namespace Kanexy\Banking\Webhooks;

use Exception;
use Illuminate\Http\Request;
use Kanexy\Banking\Strategies\AccountReadyToUse;
use Kanexy\Banking\Strategies\BeneficiaryFirewallFinished;
use Kanexy\Banking\Strategies\CardActivated;
use Kanexy\Banking\Strategies\CardAwaitingActivation;
use Kanexy\Banking\Strategies\CardClosed;
use Kanexy\Banking\Strategies\CardFailedToActivate;
use Kanexy\Banking\Strategies\CardFailedToSuspend;
use Kanexy\Banking\Strategies\CardFraudRuleBreach;
use Kanexy\Banking\Strategies\CardSuspended;
use Kanexy\Banking\Strategies\CardTransaction;
use Kanexy\Banking\Strategies\CardTransactionReceive;
use Kanexy\Banking\Strategies\EnduserFirewallFinished;
use Kanexy\Banking\Strategies\KycCheckFinished;
use Kanexy\Banking\Strategies\KycMissingData;
use Kanexy\Banking\Strategies\LedgerChanged;
use Kanexy\Banking\Strategies\TransactionAccepted;
use Kanexy\Banking\Strategies\TransactionDeclined;
use Kanexy\Banking\Strategies\TransactionPending;
use Kanexy\Banking\Strategies\TransactionPendingReview;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class WrappexWebhook
{
    private array $strategyTypeMap = [
        'ledger-changed' => LedgerChanged::class,
        'account-ready-to-use' => AccountReadyToUse::class,
        'card-activated' => CardActivated::class,
        'card-awaiting-activation' => CardAwaitingActivation::class,
        'card-failed-to-activate' => CardFailedToActivate::class,
        'card-failed-to-suspend' => CardFailedToSuspend::class,
        'card-suspended' => CardSuspended::class,
        'card-transaction' => CardTransaction::class,
        'transaction-accepted' => TransactionAccepted::class,
        'transaction-declined' => TransactionDeclined::class,
        'transaction-pending' => TransactionPending::class,
        'transaction-pending-review' => TransactionPendingReview::class,
        'card-transaction-receive' => CardTransactionReceive::class,
        'card-closed' => CardClosed::class,
        'enduser-firewall-finished' => EnduserFirewallFinished::class,
        'beneficiary-firewall-finished' => BeneficiaryFirewallFinished::class,
        'card-fraud-rule-breach' => CardFraudRuleBreach::class,
        'kyc-check-finished' => KycCheckFinished::class,
        'kyc-missing-data' => KycMissingData::class,
    ];

    public function __invoke(Request $request)
    {
        $payload = $request->input('payload');
        $strategy = $this->getStrategyForType($type = $request->input('type'));

        $strategy->handle($payload, $type);
    }

    private function getStrategyForType(string $type): WebhookHandler
    {
        if (! isset($this->strategyTypeMap[$type])) {
            throw new Exception("No strategy exists for handling type [" . $type . "].");
        }

        return new $this->strategyTypeMap[$type]();
    }
}
