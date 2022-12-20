<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardSuspended implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        $card = Card::findOrFailByRef($payload['id']);
        $card->update(['status' => $payload['status']]);
    }
}
