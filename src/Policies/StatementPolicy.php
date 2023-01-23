<?php

namespace Kanexy\Banking\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Kanexy\PartnerFoundation\Core\Enums\Permission;

class StatementPolicy
{
    use HandlesAuthorization;

    public const INDEX = 'index';

    public const EXPORT = 'export';


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
        if ($user->hasPermissionTo(Permission::STATEMENT_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        $workspaceId = request()->input('filter.workspace_id');

        return $user->workspaces()->where('workspace_id', $workspaceId)->exists();
    }

    public function export(User $user)
    {
        if ($user->hasPermissionTo(Permission::STATEMENT_EXPORT) && !$user->isSubscriber()) {
            return true;
        }

        $workspaceId = request()->input('filter.workspace_id');

        return $user->workspaces()->where('workspace_id', $workspaceId)->exists();
    }
}
