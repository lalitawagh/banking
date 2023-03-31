<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardFraudRuleBreach implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        return;
    }
}
