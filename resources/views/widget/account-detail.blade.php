<div class="intro-y col-span-12 md:col-span-4 lg:col-span-4 intro-y h-full">
    <div class="box shadow-lg p-3">
        <div class="text-lg font-medium mr-auto mt-2">Account</div>
        <ul class="mt-1">
            <li class="w-full flex justify-between my-3"><span class="font-medium w-2/4">Name</span>
                <p class="w-2/3 text-sm break-all">{{ @$account->name }}</p>
            </li>
            <li class="w-full flex justify-between my-3"><span class="font-medium w-2/4">Sort Code </span>
                <p class="w-2/3 text-sm break-all">{{ @$account->bank_code }}</p>
            </li>
            <li class="w-full flex justify-between my-3"><span class="font-medium w-2/4">Account Number </span>
                <p class="w-2/3 text-sm break-all">{{ @$account->account_number }}</p>
            </li>
            <li class="w-full flex justify-between my-3"><span class="font-medium w-2/4">IBAN </span>
                <p class="w-2/3 text-sm break-all">{{ @$account->iban_number }}</p>
            </li>
            <li class="w-full flex justify-between my-3"><span class="font-medium w-2/4">BIC/SWIFT Code </span>
                <p class="w-2/3 text-sm break-all">{{ @$account->bic_swift }}</p>
            </li>

        </ul>


    </div>
</div>
<div class="intro-y col-span-12 md:col-span-4 lg:col-span-4 intro-y h-full">
    <div class="box shadow-lg p-3 h-full">
        <div class=" text-lg font-medium mr-auto mt-2">Balance</div>
        <div class="text-2xl font-bold leading-8 p-10 text-center">
            {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($account?->balance) }}
        </div>
    </div>
</div>

<div class="intro-y col-span-12 md:col-span-4 lg:col-span-4 intro-y h-full">
    <div class="box shadow-lg p-3 h-full">
        <div class="text-lg font-medium mr-auto mt-2">Cards</div>


        @if ($card)
            <ul class="mt-1">
                {{-- <li class="w-full flex justify-between my-3 capitalize"><span class="font-medium w-2/4">Number </span> <p class="w-2/3 text-sm break-all text-right text-theme-11">@if ($card?->number){{ @$card?->number }} @else Pending @endif </p></li> --}}
                <li class="w-full flex justify-between my-3 capitalize"><span class="font-medium w-2/4">Mode </span>
                    <p class="w-2/3 text-sm break-all text-right">
                        @if ($card?->mode)
                            {{ @$card?->mode }}
                        @else
                            Pending
                        @endif
                    </p>
                </li>
                <li class="w-full flex justify-between my-3 capitalize"><span class="font-medium w-2/4">Type</span>
                    <p class="w-2/3 text-sm break-all text-right">
                        @if ($card?->type)
                            {{ @$card?->type }}
                        @else
                            Pending
                        @endif
                    </p>
                </li>
                <li class="w-full flex justify-between my-3 capitalize"><span class="font-medium w-2/4">Status </span>
                    <p
                        class="w-2/3 text-sm break-all text-right @if ($card?->status == 'requested') text-theme-11 @endif ">
                        @if ($card?->status)
                            {{ @$card?->status }}
                        @else
                            Pending
                        @endif
                    </p>
                </li>
            </ul>
        @else
            <p class="text-lg text-gray-500 p-2 h-full">
                Activate Your Card
            </p>
        @endif
    </div>
</div>
