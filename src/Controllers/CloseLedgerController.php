<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Kanexy\Banking\Mail\CloseLedgerRequestMail;
use Kanexy\Banking\Services\WrappexService;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Banking\Policies\CloseLedgerPolicy;
use Kanexy\Banking\Requests\StoreCloseLedgerRequest;
use Kanexy\PartnerFoundation\Core\Models\ArchivedMember;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Kanexy\Banking\Enums\AccountEnum;
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

        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $closeLedgerRequests = ArchivedMember::whereName('close_ledger')->latest()->paginate(10);
        } else {
            $closeLedgerRequests = ArchivedMember::whereName('close_ledger')->where('holder_id', $user->id)->latest()->paginate(10);
        }

        $workspace = null;

        if ($request->has('filter.workspace_id')) {
            $workspace = Workspace::findOrFail($request->input('filter.workspace_id'));
        }

        return view('banking::closeledger.index', compact('closeLedgerRequests', 'user', 'workspace'));
    }


    public function store(StoreCloseLedgerRequest $request)
    {

        $this->authorize(CloseLedgerPolicy::CREATE, ArchivedMember::class);

        $data = $request->validated();


        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existRequest = ArchivedMember::whereName('close_ledger')->where('holder_id', $user->id)->where('status', '!=', AccountEnum::DECLINED)->first();
        if (!is_null($existRequest)) {
            return back()->with([
                'message' => 'Already Close Ledger Request Exists!',
                'status' => 'failed'
            ]);
        }

        $workspace = Workspace::whereAdminId($user->id)->first();
        $account = $workspace->account()->first();

        if ($account->balance > 0) {
            return redirect()->back()->with([
                'message' => 'Pleases Transfer your balance, before making requests',
                'status' => 'failed',
            ]);
        }

        $archive = new ArchivedMember();
        $archive->holder()->associate($user);
        $archive->name = 'close_ledger';
        $archive->meta = $data;
        $archive->status = AccountEnum::PENDING;
        $isArchived = $archive->save();

        if ($isArchived) {
            Mail::to(auth()->user()->email)->queue(new CloseLedgerRequestMail(auth()->user()));
        }


        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Request submitted successfully.',
        ]);
    }


    public function approveRequest(Request $request)
    {
        $archivedMember = ArchivedMember::find($request->id);
        $user = User::find($archivedMember->holder_id);
        $workspace = Workspace::whereAdminId($user->id)->first();
        $account = $workspace->account()->first();

        if ($account->balance > 0) {

            Notification::sendNow($user, new CloseLedgerNotification($user));

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

        $archivedMember->update(['status' => AccountEnum::APPROVED]);
        $account->update(['status' => AccountEnum::CLOSED]);

        return redirect()->route('dashboard.banking.closeledger.index')->with([
            'status' => 'success',
            'message' => 'Request Approved',
        ]);
    }


    public function declineRequest(Request $request)
    {
        $archivedMember = ArchivedMember::find($request->id);

        $archivedMember->update(['status' => AccountEnum::DECLINED]);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Request Declined',
        ]);
    }
}
