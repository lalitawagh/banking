<?php

namespace  Kanexy\Banking\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class MembershipPolicy
{
    use HandlesAuthorization;

    public const INDEX = 'index';

    public const SHOW = 'show';

    public const UPDATE = 'update';

    public const GENERALINFO = 'showGeneralInfo';

    public const BANKINFO = 'showBankInfo';

    public const DOCUMENTATION = 'documentation';

    public const VERIFICATION = 'verification';

    public const KYC = 'kyc';


    public function index(User $user)
    {
        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function show(User $user)
    {
        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
        if ($user->hasPermissionTo(Permission::MEMBERSHIP_EDIT) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function showGeneralInfo(User $user)
    {
        $workspaceId = request()->route('workspace');
        $workspace = Workspace::find($workspaceId);

        if (empty($workspace)) {
            return false;
        }

        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }
        else
        {
            if ($workspace->users()->where('user_id', $user->id)->exists()) {
                return true;
            }
            return false;
        }
    }

    public function showBankInfo(User $user)
    {
        $workspaceId = request()->route('workspace');
        $workspace = Workspace::find($workspaceId);

        if (empty($workspace)) {
            return false;
        }

        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }
        else
        {
            if ($workspace->users()->where('user_id', $user->id)->exists()) {
                return true;
            }
            return false;
        }
    }

    public function documentation(User $user)
    {
        $workspaceId = request()->route('workspace');
        $workspace = Workspace::find($workspaceId);

        if (empty($workspace)) {
            return false;
        }

        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function verification(User $user)
    {
        $workspaceId = request()->route('workspace');
        $workspace = Workspace::find($workspaceId);

        if (empty($workspace)) {
            return false;
        }

        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return false;
    }

    public function kyc(User $user)
    {
        if ($user->hasPermissionTo(Permission::MEMBERSHIP_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        $workspaceId = request()->route('id');
        $workspace = Workspace::find($workspaceId);
        if ($workspace->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
        
    }
}
