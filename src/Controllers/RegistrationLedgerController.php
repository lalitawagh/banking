<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Kanexy\Cms\Enums\RegistrationStep;
use Kanexy\PartnerFoundation\Banking\Enums\BankEnum;
use Kanexy\PartnerFoundation\Core\Enums\ServiceType;
use Kanexy\PartnerFoundation\Core\Models\Document;
use Kanexy\PartnerFoundation\Core\Models\DocumentType;

class RegistrationLedgerController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $customer */
        $customer = Auth::user();
        $workspace = $customer->workspaces()->first();
        $address = $customer->addresses()->where('type','home')->first();
        $documents = collect($customer->documents);

        if(! $documents->isEmpty())
        {
            if(! $workspace->documents->isEmpty())
            {
                $workspaceDocuments = $workspace->documents()->get();
                foreach($workspaceDocuments as $document)
                {
                    $documents->push($document);
                }

            }
        } else {
            $documents = $workspace->documents()->get();
        }

        $DocumentTypeId=DocumentType::where('key','verification_video')->first()->id;
		$DocumentSelfieTypeId=DocumentType::where('key','verification_image')->first()->id;
        $offeredServices = $workspace->services()->where('type', ServiceType::OFFER)->get();
        $requiredServices = $workspace->services()->where('type', ServiceType::REQUIRED)->get();
        $membership = $workspace->getMembership();
        $workspaceAddress = $workspace->addresses()->first();
        $currentOfficer = $workspace->contacts()->where('meta->tag','current_officer')->first();

        return view("banking::registration.company-preview", compact("DocumentTypeId", "DocumentSelfieTypeId", "customer", "workspace", "address", "documents", "offeredServices", "requiredServices", "workspaceAddress" , "currentOfficer"));
    }

    public function store()
    {
        if (request()->has('callback_url') && request()->input('callback_url')) {
            return Document::redirectForReUpload(request()->input('callback_url'),request()->input('document_type'));
        }

        /** @var User $user */
        $user = Auth::user();

        /** @var Workspace $workspace */
        $workspace = $user->workspaces()->first();

        $workspace->account_confirm_status = BankEnum::SUBMITTED;
        $workspace->save();

        $workspaceServices = $workspace->appServices;

        if ($workspaceServices->contains('key', 'bank') && ! $workspace->hasAccount()) {

            if (! $workspace->isRegisteredOnPartner()) {
                $workspace->createUserOnPartner();
            }

            $workspace->createAccountOnPartner();
        }

        $user->logRegistrationStep(RegistrationStep::ACCOUNT_PREVIEW);

        if ($workspace->hasAccount()) {
            $nextRoute = $user->getNextRegistrationRoute();
            if(isset($nextRoute))
            {
                return redirect($nextRoute->getUrl());
            }
        }
        return redirect()->route('dashboard.index');
    }

    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var Workspace $workspace */
        $workspace = $user->workspaces()->first();

        /** @var Account $account */
        $ledger = $workspace->accounts()->first();

        $user->logRegistrationStep(RegistrationStep::LEDGER);

        return view("banking::registration.ledger",  compact('user', 'ledger', 'workspace'));
    }
}
