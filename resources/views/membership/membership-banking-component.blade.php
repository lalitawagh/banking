

<div class="text-center sm:text-left bg-gray-200 col-span-12 sm:col-span-6 xxl:col-span-6 box p-5 cursor-pointer zoom-in">
    @if ($account?->account_number)
    <div class="sm:pl-1">
        <div class="font-medium text-theme-1 dark:text-theme-10 text-l">Sort Code</div>
        <div class="text-gray-600 break-all"> {{ $account?->bank_code }} </div>
    </div>
    <div class="sm:pl-1">
        <div class="font-medium text-theme-1 dark:text-theme-10 text-l">Account Number</div>
        <div class="text-gray-600 break-all">{{ $account?->account_number }} </div>
    </div>
    <div class="sm:pl-1">
        <div class="font-medium text-theme-1 dark:text-theme-10 text-l">Account Balance</div>
        <div class="text-gray-600 break-all">£ {{ $account?->balance ? $account?->balance : '0.00' }} </div>
    </div>
@endif
</div>



