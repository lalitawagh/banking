<?php

namespace Kanexy\Banking\Menu;

use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Menu\Contracts\Item;
use Kanexy\Cms\Menu\MenuItem;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Core\Helper;

class BankingMenuItem extends Item
{
    public int $priority = 1002;

    protected string $label = 'Banking';

    protected string $icon = 'globe';

    public function getIsVisible(): bool
    {
        /** @var $user App\Model\User */
        $user = Auth::user();

        if ($user->isSubscriber() && ($user->is_banking_user == 1)) {
            return true;
        }

        if ($user->hasAnyPermission([Permission::TRANSACTION_VIEW, Permission::TRANSACTION_CREATE, Permission::CONTACT_VIEW, Permission::CONTACT_CREATE], Permission::CLOSE_LEDGER_VIEW)  && !$user->isSubscriber()) {
            if (config('services.disable_banking') == true) {
                return false;
            }
            return true;
        }

        return false;
    }

    public function getSubmenu(): array
    {
        /** @var $user App\Model\User */
        $user = Auth::user();

        $menus = [];

        if ($user->hasPermissionTo(Permission::TRANSACTION_VIEW)) {
            $menus[] = new MenuItem('Transactions', 'activity', url: route('dashboard.banking.transactions.index', ['filter' => ['workspace_id' => app('activeWorkspaceId')]]));
        }

        if ($user->hasPermissionTo(Permission::TRANSACTION_CREATE) && $user->isSubscriber()) {
            $menus[] =  new MenuItem('Send Money', 'activity', url: route('dashboard.banking.payouts.index', ['workspace_id' => app('activeWorkspaceId')]));
        }

        if ($user->hasPermissionTo(Permission::CONTACT_VIEW)) {
            $menus[] = new MenuItem('Beneficiaries', 'activity', url: route('dashboard.banking.beneficiaries.index', ['filter' => ['workspace_id' => app('activeWorkspaceId')]]));
        }

        if ($user->hasPermissionTo(Permission::STATEMENT_VIEW)) {
            $menus[] =  new MenuItem('Statement', 'activity', url: route('dashboard.banking.statement.index', ['filter' => ['workspace_id' => app('activeWorkspaceId')]]));
        }
        if ($user->hasPermissionTo(Permission::CLOSE_LEDGER_VIEW)) {
            $menus[] =  new MenuItem('Close Bank Account', 'activity', url: route('dashboard.banking.closeledger.index', ['filter' => ['workspace_id' => app('activeWorkspaceId')]]));
        }

        return $menus;
    }
}
