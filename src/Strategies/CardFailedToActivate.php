<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardFailedToActivate implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        $card = Card::findOrFailByRef($payload['id']);
        $card->update(['status' => $payload['status']]);
    }
}
