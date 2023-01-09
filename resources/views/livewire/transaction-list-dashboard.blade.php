<div class="intro-y mt-0">
    <div class="sm:flex justify-end flex-wrap items-center sm:py-1 border-b border-gray-200 dark:border-dark-5 gap-1">
        <x-list-view-filters />
    </div>
</div>
<div class="overflow-x-auto overflow-y-hidden">
    <div class="table w-full py-2 px-0">
        <table class="shroting display table table-report -mt-2">
            <thead class="short-wrp dark:bg-darkmode-400 dark:border-darkmode-400">
                <tr class="dark:bg-darkmode-400 dark:border-darkmode-400">
                    <th class="whitespace-nowrap text-left">Transaction ID
                        <span class="flex short-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                        </span>
                    </th>
                    <th class="whitespace-nowrap text-left">Source
                        <span class="flex short-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                        </span>
                    </th>
                    <th class="whitespace-nowrap text-left">Date & Time
                        <span class="flex short-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                        </span>
                    </th>
                    <th class="whitespace-nowrap text-left">Account Holder Name
                        <span class="flex short-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                        </span>
                    </th>
                    <th class="whitespace-nowrap text-left text-right">Amount
                        <span class="flex short-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                        </span>
                    </th>
                    <th class="whitespace-nowrap text-left">Status

                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $index => $transaction)
                    <tr @if ($index % 2 === 0) class="" @endif>
                        <td class="whitespace-nowrap text-left active-clr">
                            {{ $transaction->prefix }}{{ $transaction->urn }}</td>
                        <td class="whitespace-nowrap text-left">{{ ucfirst($transaction->payment_method) }}</td>
                        <td class="whitespace-nowrap text-left whitespace-nowrap">
                            {{ @$transaction->getLastProcessDateTime()->format($defaultDateFormat . ' ' . $defaultTimeFormat) }}
                        </td>
                        <td class="whitespace-nowrap text-left">
                            @if ($transaction->type === 'debit')
                                {{ @$transaction->meta['beneficiary_name'] }}
                            @else
                                {{ @$transaction->meta['sender_name'] }}
                            @endif
                        </td>
                        <td
                            class="text-right whitespace-nowrap text-left @if ($transaction->type === 'debit') text-theme-6 @else text-success @endif">
                            {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction?->amount) }}</td>
                        <td class="whitespace-nowrap text-left whitespace-nowrap">{{ ucfirst($transaction->status) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
