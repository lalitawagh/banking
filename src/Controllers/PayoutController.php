<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Cms\I18N\Models\Country;
use Kanexy\Cms\Notifications\SmsOneTimePasswordNotification;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Banking\Models\Account;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\Banking\Policies\TransactionPolicy;
use Kanexy\Banking\Requests\MakePayoutRequest;
use Kanexy\Banking\Services\PayoutService;
use Kanexy\Cms\Notifications\EmailOneTimePasswordNotification;
use Kanexy\PartnerFoundation\Cxrm\Models\Contact;
use Kanexy\PartnerFoundation\Saas\Models\PlanSubscription;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Kanexy\PartnerFoundation\Workspace\Enums\WorkspaceStatus;
class PayoutController extends Controller
{
    private PayoutService $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(Request $request)
    {
        $this->authorize(TransactionPolicy::CREATE, Transaction::class);

        $workspace = Workspace::findOrFail($request->input('workspace_id'));

        $countries = Country::get();

        $defaultCountry = Setting::getValue('default_country');

        $accounts = Account::forHolder($workspace)->latest()->get();

        $beneficiaries = Contact::beneficiaries()->whereRefType('wrappex')->forWorkspace($workspace)->verified()->latest()->get();

        return view("banking::banking.payouts", compact('workspace', 'beneficiaries', 'accounts', 'countries', 'defaultCountry'));
    }

    public function store(MakePayoutRequest $request)
    {
        $transactionOtpService = Setting::getValue('transaction_otp_service');
        $workspace = Workspace::findOrFail($request->input('workspace_id'));

        if ($workspace->status == WorkspaceStatus::INACTIVE){

              return redirect()->back();
        }
        /** @var Account $sender */
        $sender = Account::findOrFail($request->input('sender_account_id'));

        /** @var Contact $beneficiary */
        $beneficiary = Contact::findOrFail($request->input('beneficiary_id'));

        $transaction = $this->payoutService->initialize($sender, $beneficiary, $request->validated());

        if($transactionOtpService == 'email')
        {
            if (config('services.disable_email_service') == false) {
                $transaction->notify(new EmailOneTimePasswordNotification($transaction->generateOtp("email")));
            }else
            {
                $transaction->generateOtp("email");
            }

            return $transaction->redirectForVerification(URL::temporarySignedRoute('dashboard.banking.payouts.verify', now()->addMinutes(30), ["id" => $transaction->id]), 'email');
        }else
        {
            if (config('services.disable_sms_service') == false) {
                $transaction->notify(new SmsOneTimePasswordNotification($transaction->generateOtp("sms")));
            }else
            {
                $transaction->generateOtp("sms");
            }

            return $transaction->redirectForVerification(URL::temporarySignedRoute('dashboard.banking.payouts.verify', now()->addMinutes(30), ["id" => $transaction->id]), 'sms');
        }
    }

    function verify(Request $request)
    {
        $transaction = Transaction::find($request->query('id'));
        $sender = Account::findOrFail($transaction->meta['sender_id']);
       
        try {
            $this->payoutService->process($transaction);
            PlanSubscription::reduceFeatureLimit($sender->workspaces()->first(),'Free Transactions');

        } catch (\Exception $exception) {
            if ($exception->getCode() === 500) {
                return redirect()->route("dashboard.banking.payouts.index", ["workspace_id" => $transaction->workspace_id])->with([
                    'message' => 'Something went wrong. Please try again later.',
                    'status' => 'failed',
                ]);
            }

            throw $exception;
        }

        return redirect()->route("dashboard.banking.transactions.index", ['filter' => ['workspace_id' => $transaction->workspace_id]])->with([
            'message' => 'Processing the payment. It may take a while.',
            'status' => 'success',
        ]);
    }

    public static function payoutInitialize($sender,$beneficiary,$info){
        /** @var Account $sender */
        $sender = Account::findOrFail($request->input('sender_account_id'));
    }
}
