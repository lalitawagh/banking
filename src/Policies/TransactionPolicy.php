<?php

namespace Kanexy\Banking\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class TransactionPolicy
{
    use HandlesAuthorization;

    public const INDEX = 'index';

    public const SHOW = 'show';

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
       
        if ($user->hasPermissionTo(Permission::TRANSACTION_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        if (! request()->has('filter.workspace_id')) {
            return false;
        }

        $workspaceId = request()->input('filter.workspace_id');

        return $user->workspaces()->where('workspace_id', $workspaceId)->exists();
    }

    public function show(User $user, Transaction $transaction)
    {
      
        if ($user->hasPermissionTo(Permission::TRANSACTION_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return $user->workspaces()->where('workspace_id', $transaction->workspace_id)->exists();
    }

    public function create(User $user)
    {
       
        if ($user->hasPermissionTo(Permission::TRANSACTION_CREATE) && !$user->isSubscriber()) {
            return true;
        }


        $workspaceId = request()->input('workspace_id', request()->input('filter.workspace_id'));

        if (empty($workspaceId)) {
            return false;
        }

        $workspace = Workspace::findOrFail($workspaceId);

        if ($workspace->users()->where('user_id', $user->id)->exists()) {

            return true;
        }

        return false;
    }

    public function edit(User $user, Transaction $transaction)
    {
      
        if ($user->hasPermissionTo(Permission::TRANSACTION_EDIT) && !$user->isSubscriber()) {
            return true;
        }

        return $user->workspaces()->where('workspace_id', $transaction->workspace_id)->exists();
    }
}
