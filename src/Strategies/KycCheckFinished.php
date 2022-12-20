<?php

namespace Kanexy\Banking\Strategies;

use Kanexy\Cms\Models\UserSetting;
use Kanexy\PartnerFoundation\Core\Interfaces\WebhookHandler;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class KycCheckFinished implements WebhookHandler
{
    public function handle(array $payload, string $type)
    {
        /** @var Workspace $workspace */
        $workspace = Workspace::findOrFailByRef($payload['id']);

        UserSetting::updateOrCreate([
            'user_id' => $workspace->admin_id,
            'key' => 'railsbank_kyc',
        ], [
            'value' =>  $payload['meta'],
        ]);
    }
}
