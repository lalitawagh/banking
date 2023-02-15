

<div class="text-center sm:text-left bg-gray-200 col-span-12 sm:col-span-6 xxl:col-span-6 box p-5 cursor-pointer zoom-in">
    @if ($account?->account_number)
    <div class="flex items-center px-3 pb-2 sm:pb-2  dark:border-dark-5">
        <span
            class="dark:bg-darkmode-400 dark:border-darkmode-400 mr-2 flex items-center p-1 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
            <i data-lucide="globe" class="w-4 h-4"></i>
        </span>

        <span class="font-medium text-small mr-auto">
            {{ $account?->bank_code }} / {{ $account?->account_number }}
        </span>

    </div>
    <div class="sm:pl-5">
        <div class="font-medium text-theme-1 dark:text-theme-10 text-l">Account Balance</div>
        <div class="text-gray-600 break-all">£ {{ $account?->balance ? $account?->balance : '0.00' }} </div>
    </div>
@endif
</div>



