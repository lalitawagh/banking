<?php

namespace Kanexy\Banking\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\AccountMeta;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\PartnerFoundation\Core\Enums\WorkspaceStatus;
use Kanexy\PartnerFoundation\Core\Enums\WorkspaceType;
use Kanexy\PartnerFoundation\Core\Models\RiskMgntAnswer;
use Kanexy\PartnerFoundation\Membership\Enums\MembershipStatus;
use Kanexy\PartnerFoundation\Membership\Models\Membership;
use Kanexy\PartnerFoundation\Membership\Policies\MembershipPolicy;
use Kanexy\PartnerFoundation\Workspace\Models\LedgerVerification;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Kanexy\PartnerFoundation\Workspace\Models\WorkspaceMeta;
use Kanexy\PartnerFoundation\Workspace\Notifications\WorkspaceActivatedNotification;

class MembershipComponentController extends Controller
{

    public function showBankInformation($workspaceId)
    {
        $this->authorize(MembershipPolicy::BANKINFO, Membership::class);

        $workspace = Workspace::findOrFail($workspaceId);
        $membership = $workspace->memberships()->first();
        $user = $workspace->users()->first();
        $account = Account::forHolder($workspace)->first();
        $risk_mgnt_answers = RiskMgntAnswer::where('holder_id', $membership->id)->first();
        $score = RiskMgntAnswer::where('holder_id', $membership->id)->sum('score');
        $riskscore = Setting::getValue("risk_scores");

        return view("banking::membership.bank-information", compact('membership', 'workspace', 'user', 'account', 'score', 'riskscore', 'risk_mgnt_answers'));
    }

    public function showConfigurationInformation($workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);
        $membership = $workspace->memberships()->first();
        $user = $workspace->users()->first();
        $account = Account::forHolder($workspace)->first();
        $risk_mgnt_answers = RiskMgntAnswer::where('holder_id', $membership->id)->first();
        $score = RiskMgntAnswer::where('holder_id', $membership->id)->sum('score');
        $riskscore = Setting::getValue("risk_scores");

        return view("banking::membership.configuration", compact('membership', 'workspace', 'user', 'account', 'score', 'riskscore', 'risk_mgnt_answers'));
    }

    public function storeVerification(Request $request)
    {
        $this->authorize(MembershipPolicy::UPDATE, Membership::class);

        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $workspace = $user->workspaces()->first();
        $workspaceMeta = WorkspaceMeta::where(['key' => 'address_proof_provided','workspace_id' => $workspace->id])->first();

        $support_verification = false;
        $compliance_verification = false;
        $ledger = Account::forHolder($workspace)->first();

        if($user->checkAccountRegistrationCompleted($user) == false)
        {
            return redirect()->back()->with(['status'=>'failed','message'=>"Incomplete registration, can't activate the account."]);
        }

        if($workspace->is_registered == true)
        {
            $count = (!is_null($workspaceMeta) && $workspaceMeta?->value == 'false') ? 3 : 4;
        }else{
            $count = 6;
        }
      
        if($request->has("support") && count($request->input("support"))==$count)
        {
            $support_verification=true;
        }
       
        if($request->has("compliance") && count($request->input("compliance"))==$count)
        {
            $compliance_verification=true;
        }

        if($request->has("support"))
        {
            foreach($request->support as $key=>$value)
            {
                LedgerVerification::updateOrCreate(['ledger_id'=>$ledger?->id,'team'=>'support','step'=>$key],['status'=>$value,'user_id'=> $user_id]);
            }
        }
        if($request->has("compliance"))
        {
            foreach($request->compliance as $key=>$value)
            {
                LedgerVerification::updateOrCreate(['ledger_id'=>$ledger?->id,'team'=>'compliance','step'=>$key],['status'=>$value,'user_id'=> $user_id]);
            }
        }

        if($support_verification && $compliance_verification)
        {
            if(!is_null( $ledger))
            {
                $ledger->status="active";
                $ledger->save();
            }


            $membership=$workspace->memberships()->first();

            $membership->update(['status'=>MembershipStatus::ACTIVE]);
            $workspace->update(['status'=>WorkspaceStatus::ACTIVE]);
            $workspace->admin->notify(new WorkspaceActivatedNotification($ledger,$user));

            /*Upload KYC to railsbank on account activation and for individual users*/
            if($workspace->type == WorkspaceType::INDIVIDUAL && !is_null( $ledger))
            {
                $workspace->uploadRailsbankKyc();
            }
        }

        return redirect()->back()->with(['status'=>'success','message'=>"Ledger Verification Updated"]);
    }

    public function storeConfiguration(Request $request, $ledger_id)
    {
        $ledger = Account::find($ledger_id);

        foreach($request->input("setting") as $setting_key => $setting_value)
        {
            AccountMeta::updateOrCreate(['key' => $setting_key, 'account_id' => $ledger->getKey()],['value' => $setting_value]);
        }

        return redirect()->back()->with(['status'=>'success','message'=>"Ledger Configuration Updated"]);
    }
}
