<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Banking\Policies\StatementPolicy;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Statement;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class StatementController extends Controller
{
    public function index(Request $request, Account $account)
    {
        $this->authorize(StatementPolicy::INDEX, Statement::class);

        $user = Auth::user();
        $workspace = null;

        if ($request->has('filter.workspace_id')) {
            $workspace = Workspace::findOrFail($request->input('filter.workspace_id'));
        }

        return view("banking::banking.statement", compact('workspace', 'user'));
    }
}
