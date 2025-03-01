

<div class="flex flex-col px-3 text-center sm:text-left bg-gray-300 box py-2 cursor-pointer">
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



