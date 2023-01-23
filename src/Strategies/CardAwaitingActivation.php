<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;

class CardAwaitingActivation implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        $card = Card::findOrFailByRef($payload['id']);
        $card->update([
            'expiry_date' => $payload['expiry_date'],
            'number' =>  $payload['token'],
            'status' => $payload['status'],
        ]);
    }
}
