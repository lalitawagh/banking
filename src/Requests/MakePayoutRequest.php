<?php

namespace Kanexy\Banking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kanexy\Cms\Rules\Reference;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\AccountMeta;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\Banking\Policies\TransactionPolicy;
use Kanexy\PartnerFoundation\Membership\Models\MembershipLog;
use Kanexy\PartnerFoundation\Saas\Models\PlanSubscription;

class MakePayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can(TransactionPolicy::CREATE, Transaction::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "sender_account_id" => ["required", "exists:accounts,id"],
            "beneficiary_id" => ["required", "exists:contacts,id"],
            "reference" => ["nullable", new Reference],
            "amount" => ["required", "numeric", "min:0.01"],
            "note" => ["nullable", "string"],
            "attachment" => ["nullable", "max:5120", "mimes:png,jpg,jpeg", "file"],
        ];
    }

    public function messages()
    {
        return [
            'attachment.max' => 'File size should not exceed 5MB.',
        ];
    }

    public function attributes()
    {
        return [
            "sender_account_id" => "account number",
            "beneficiary_id" => "beneficiary",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $senderAccountId = $this->input('sender_account_id');

            if (!is_null($senderAccountId)) {
                /** @var Account $senderAccount */
                $senderAccount = Account::with('workspaces')->findOrFail($senderAccountId);
                $amount = (float) $this->input('amount');
                $transactionLimit = AccountMeta::where(['key' => 'max_per_txn_limit','account_id' => $senderAccount->getKey()])->first();
                $membershipLog = MembershipLog::where(['key' => 'Free Transactions','holder_id' => $senderAccount->workspaces()->first()->memberships()->first()->id])->first();

                $feature= PlanSubscription::checkFeatureLimit($senderAccount->workspaces()->first(),'Free Transactions');

                if ($senderAccount->balance < $amount) {
                    $validator->errors()->add('amount', 'Insufficient balance in the account.');
                }

                if ($transactionLimit?->value != 0 && $transactionLimit?->value < $amount) {
                    $validator->errors()->add('amount', 'You have exceeded the maximum transaction limit');
                }


                if(!is_null(@$feature['used']) && @$feature['used'] <= 0 || $membershipLog?->value >= $feature['used'])
                {
                    $validator->errors()->add('feature', 'The Transaction Feature limit is over for this subscription.');
                }else if(is_null(@$feature['used']))
                {
                    if($feature?->used <= 0)
                    {
                        $validator->errors()->add('feature', 'The Transaction Feature limit is over for this subscription.');
                    }
                }

            }
        });
    }
}
