<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class EnduserFirewallFinished implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        return;
    }
}
