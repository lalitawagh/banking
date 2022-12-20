<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Banking\Enums\TransactionStatus;
use Kanexy\Banking\Policies\CloseLedgerPolicy;
use Kanexy\Banking\Requests\StoreCloseLedgerRequest;
use Kanexy\PartnerFoundation\Core\Models\ArchivedMember;
use Kanexy\PartnerFoundation\Core\Services\WrappexService;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Kanexy\Cms\Enums\Status;
use Kanexy\Banking\Notifications\CloseLedgerNotification;

class CloseLedgerController extends Controller
{
    private WrappexService $service;

    public function __construct(WrappexService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $this->authorize(CloseLedgerPolicy::VIEW, ArchivedMember::class);
        if (auth()->user()->isSuperAdmin()) {
            $closeLedgerRequests = ArchivedMember::whereName('close_ledger')->paginate();
        } else {
            $closeLedgerRequests = ArchivedMember::whereName('close_ledger')->where('holder_id', auth()->user()->id)->paginate();
        }


        return view('partner-foundation::closeledger.index', compact('closeLedgerRequests'));
    }

    public function store(StoreCloseLedgerRequest $request)
    {
        $this->authorize(CloseLedgerPolicy::CREATE, ArchivedMember::class);

        $data = $request->validated();


        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existRequest = ArchivedMember::where('holder_id', $user->id)->where('status', Status::PENDING)->first();

        if (!is_null($existRequest)) {
            return back()->with([
                'message' => 'Already Close Ledger Request Exists!',
                'status' => 'failed'
            ]);
        }

        $workspace  = Workspace::whereAdminId($user->id)->first();
        $account = $workspace->account()->first();

        if ($account->balance > 0) {
            return redirect()->back()->with([
                'message' => 'Please Transfer your balance, before making requests',
                'status' => 'failed',
            ]);
        }

        $archive = new ArchivedMember();
        $archive->holder()->associate($user);
        $archive->name = 'close_ledger';
        $archive->meta = $data;
        $archive->status = Status::PENDING;
        $archive->save();
        return redirect()->route('dashboard.index')->with([
            'status' => 'success',
            'message' => 'Request submitted successfully.',
        ]);
    }

    public function approveRequest(Request $request)
    {
        $archivedMember = ArchivedMember::find($request->id);
        $user = User::find($archivedMember->holder_id);
        $workspace  = Workspace::whereAdminId($user->id)->first();
        $account = $workspace->account()->first();

        $admin = User::whereHas("roles", function ($q) {
            $q->where("name", "super_admin");
        })->get();

        if ($account->balance > 0) {

            Notification::sendNow($user, new  CloseLedgerNotification($user));

            return redirect()->back()->with([
                'message' => 'User having balance in the account',
                'status' => 'failed',
            ]);
        }

        try {
            $this->service->closeAccount($account->ref_id);
        } catch (\Exception $exception) {
            if ($exception->getCode() === 500) {
                return redirect()->route("dashboard.banking.closeledger.index")->with([
                    'message' => 'Something went wrong. Please try again later.',
                    'status' => 'failed',
                ]);
            }

            throw $exception;
        }

        $archivedMember->update(['status' => Status::APPROVE]);

        return redirect()->route('dashboard.banking.closeledger.index')->with([
            'status' => 'success',
            'message' => 'Request Approved',
        ]);
    }
    public function declineRequest(Request $request)
    {
        $archivedMember = ArchivedMember::find($request->id);

        $archivedMember->update(['status' => Status::DECLINED]);

        return redirect()->route('dashboard.banking.closeledger.index')->with([
            'status' => 'success',
            'message' => 'Request Declined',
        ]);
    }
}
