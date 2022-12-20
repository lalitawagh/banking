<?php

namespace Kanexy\Banking\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Kanexy\Banking\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class CloseLedgerPolicy
{
    use HandlesAuthorization;

    public const VIEW = 'index';

    public const CREATE = 'create';

    public const EDIT = 'edit';

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permission::CLOSE_LEDGER_VIEW);
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo(Permission::CLOSE_LEDGER_CREATE);
    }
    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permission::CLOSE_LEDGER_EDIT);
    }
}
