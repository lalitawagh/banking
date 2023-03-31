<?php

namespace Kanexy\Banking\Menu;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Menu\Contracts\Item;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Core\Helper;

class CardsMenuItem extends Item
{
    public int $priority = 1003;

    protected string $label = 'Cards';

    protected string $icon = 'credit-card';

    public function getIsVisible(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSubscriber() && ($user->is_banking_user == 1)) {
            return true;
        }

        if ($user->hasPermissionTo(Permission::CARD_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function getUrl(): string
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasPermissionTo(Permission::CARD_VIEW)) {
            return route('dashboard.cards.index', ['filter' => ['workspace_id' => app('activeWorkspaceId')]]);
        }

        return route('dashboard.cards.index');
    }
}
