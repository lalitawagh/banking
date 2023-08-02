<?php

namespace Kanexy\Banking\Livewire;

use Carbon\Carbon;
use Kanexy\Cms\I18N\Models\Country;
use Kanexy\Cms\Models\OneTimePassword;
use Kanexy\Cms\Notifications\EmailOneTimePasswordNotification;
use Kanexy\Cms\Notifications\SmsOneTimePasswordNotification;
use Kanexy\Cms\Setting\Models\Setting;
use Livewire\Component;

class OtpVerificationComponent extends Component
{

    public $countries;

    public $defaultCountry;

    public $user;

    public $workspace;

    public $expiresIn = 10;

    public $code;

    public $oneTimePassword;

    public $sent_resend_otp;

    public $requestQueries;

    public function mount($countries,$defaultCountry,$workspaceId)
    {

        $this->countries = $countries;

        $this->defaultCountry = $defaultCountry;

        $this->requestQueries = request()->all();

        $this->user = session('user');

        $this->workspace_id = $workspaceId;

        $this->oneTimePassword = OneTimePassword::find(session('oneTimePassword'));
    }

    public function resendOtp()
    {
        $oneTimePassword = OneTimePassword::find(session('oneTimePassword'));
        $transactionOtpService = Setting::getValue('transaction_otp_service');

        if (Carbon::now()->gt($oneTimePassword->expires_at) && $oneTimePassword->verified_at == null) {

            $oneTimePassword->update(['code' => rand(100000, 999999), 'expires_at' => now()->addMinutes(OneTimePassword::getExpiringDuration())]);
        }

        if($transactionOtpService == 'email')
        {
            if (config('services.disable_email_service') == false) {
                $oneTimePassword->holder->notify(new EmailOneTimePasswordNotification($oneTimePassword));
            }
        }else{
            if(config('services.disable_sms_service') == false){
                $oneTimePassword->holder->notify(new SmsOneTimePasswordNotification($oneTimePassword));
            }
        }

        $this->sent_resend_otp = true;
    }

    public function verifyOtp()
    {

        $data = $this->validate([
            'code' => 'required|min:6',
        ],[
            'code.required' => 'The otp field is required.',
            'code.min' => 'The otp must be 6 digits.',
        ]);

        $this->user = session('contact');

        $oneTimePassword = $this->user->oneTimePasswords()->first();
        $manualOtp = Setting::getValue('otp');

        if (isset($manualOtp) && ($manualOtp == $data['code'])) {
            $oneTimePassword->update(['verified_at' => now()]);

            return redirect()->route('dashboard.banking.payouts.index', ['workspace_id' => $this->workspace_id])->with([
                'status' => 'success',
                'message' => 'The beneficiary created successfully.',
            ]);
        }

        if ($oneTimePassword->code !== $data['code']) {

            $this->addError('code', 'The otp you entered did not match.');

        } else if (now()->greaterThan($oneTimePassword->expires_at)) {

            $this->addError('code', 'The otp you entered has expired.');

        } else {

            $oneTimePassword->update(['verified_at' => now()]);

            return redirect()->route('dashboard.banking.payouts.index', ['workspace_id' => $this->workspace_id])->with([
                'status' => 'success',
                'message' => 'The beneficiary created successfully.',
            ]);
        }
    }

    public function render()
    {
        return view('banking::banking.livewire.otp-verification');
    }
}
