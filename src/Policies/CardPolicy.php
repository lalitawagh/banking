<?php

namespace Kanexy\Banking\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Enums\Permission;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class CardPolicy
{
    use HandlesAuthorization;

    public const INDEX = 'index';

    public const SHOW = 'show';

    public const CREATE = 'create';

    public const ACTIVATE = 'activate';

    public const APPROVE = 'approve';

    public const CLOSE = 'close';

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
        if ($user->hasPermissionTo(Permission::CARD_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        if (! request()->has('filter.workspace_id')) {
            return false;
        }

        $workspaceId = request()->input('filter.workspace_id');

        return $user->workspaces()->where('workspace_id', $workspaceId)->exists();
    }

    public function show(User $user, Card $card)
    {
        if ($user->hasPermissionTo(Permission::CARD_VIEW) && !$user->isSubscriber()) {
            return true;
        }

        return $user->workspaces()->where('workspace_id', $card->workspace_id)->exists();
    }

    public function create(User $user)
    {
        if ($user->hasPermissionTo(Permission::CARD_CREATE) && !$user->isSubscriber()) {
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

    public function activate(User $user, Card $card)
    {

        if ($card->status !== 'awaiting-activation' && $card->status !== 'created' && $card->status !== 'approved') {
           return false;
        }

        return $user->hasPermissionTo(Permission::CARD_ACTIVATE);
    }

    public function approve(User $user, Card $card)
    {
        if ($card->status !== 'requested') {
            return false;
        }

        return $user->hasPermissionTo(Permission::CARD_APPROVE);
    }

    public function close(User $user, Card $card)
    {
        if ($card->status !== 'active') {
           return false;
        }

        return $user->hasPermissionTo(Permission::CARD_CLOSE);
    }
}
