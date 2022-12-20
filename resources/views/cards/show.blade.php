@extends('cms::dashboard.layouts.default')

@section('title', 'Cards | ' . $card->name)

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box p-8 relative overflow-hidden intro-y">
                <div class="flex flex-col lg:flex-row items-center dark:border-dark-5 -mx-5">
                    <div
                        class="flex-1 dark:text-gray-300 px-5 border-r border-gray-200 dark:border-dark-5 border-t lg:border-t-0 pt-5 lg:pt-0">
                        <div class="flex flex-col justify-center items-center lg:items-start">
                            <div class="flex justify-center items-center mb-4">
                                <h2 class="text-3xl font-medium leading-none">{{ $card->name }}</h2>
                            </div>

                            <div class="flex">
                                <div class="truncate sm:whitespace-normal flex items-center"><i data-lucide="credit-card"
                                        class="w-4 h-4 mr-2"></i> {{ \Illuminate\Support\Str::title($card->mode) }}</div>
                                <div class="truncate sm:whitespace-normal flex items-center ml-3"><i data-lucide="calendar"
                                        class="w-4 h-4 mr-2"></i> {{ $card->expiry_date ?? 'Unknown' }}</div>
                                <div
                                    class="truncate sm:whitespace-normal flex items-center ml-3
                                    @if (\Illuminate\Support\Str::title($card->status) == 'Approved' ||
                                        \Illuminate\Support\Str::title($card->status) == 'Active') text-success
                                    @elseif (\Illuminate\Support\Str::title($card->status) == 'Requested')
                                    text-warning
                                    @else
                                    text-danger @endif">
                                    <i data-lucide="circle" class="w-4 h-4 mr-2"></i>
                                    {{ \Illuminate\Support\Str::title($card->status) }}
                                </div>
                            </div>
                            @if ($card->billingAddress?->toString())
                                <div class="sm:whitespace-normal flex mt-2"><i data-lucide="map-pin"
                                        class="w-4 h-4 mr-2"></i> {{ $card->billingAddress?->toString() }}</div>
                            @endif
                        </div>
                    </div>

                    <div
                        class="flex-1 flex items-center justify-center px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
                        @if ($cardImage)
                            <img alt="{{ $card->name }}" class="rounded-md sm:h-48 sm:w-3/4 sm:object-bottom"
                                data-action="zoom" src="{{ $cardImage }}">
                        @else
                            <img alt="{{ $card->name }}" class="rounded-md sm:h-48 sm:w-3/4 sm:object-bottom"
                                data-action="zoom" src="{{ asset('dist/images/card_semple.png') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Card Transactions
                    </h2>
                </div>

                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="short-wrp dark:bg-darkmode-400 dark:border-darkmode-400">
                                <tr>
                                    <th class="whitespace-nowrap">#</th>
                                    <th class="whitespace-nowrap">Date & Time
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
                                    <th class="whitespace-nowrap">Beneficiary Name
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
                                    <th class="whitespace-nowrap">Bank Sort Code
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
                                    <th class="whitespace-nowrap">Bank Account No.
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
                                    <th class="whitespace-nowrap text-right">Amount
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
                                    <th class="whitespace-nowrap">Status</th>
                                    {{-- <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $transaction)
                                    <tr @if ($index % 2 === 0) class="" @endif>
                                        <td class="border-b dark:border-dark-5 whitespace-nowrap">
                                            <a class="active-clr" href="javascript:void(0);" data-tw-toggle="modal"
                                                data-tw-target="#transaction-detail-modal"
                                                onclick="Livewire.emit('showTransactionDetail', {{ $transaction->id }})">{{ $transaction->urn }}</a>
                                        </td>
                                        <td class="border-b dark:border-dark-5 whitespace-nowrap">
                                            {{ $transaction->getLastProcessDateTime()->format($defaultDateFormat . ' ' . $defaultTimeFormat) }}
                                        </td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ $transaction->meta['beneficiary_name'] }}</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ $transaction->meta['beneficiary_bank_code'] }}</td>
                                        <td class="border-b dark:border-dark-5">
                                            {{ $transaction->meta['beneficiary_bank_account_number'] }}</td>
                                        <td
                                            class="text-right border-b dark:border-dark-5 @if ($transaction->type === 'debit') text-theme-6 @else text-success @endif">
                                            {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction?->amount) }}
                                        </td>
                                        <td class="border-b dark:border-dark-5 whitespace-nowrap">
                                            {{ ucfirst($transaction->status) }}</td>
                                        {{-- <td class="border-b dark:border-dark-5">
                                        <div class="dropdown">
                                            <button class="dropdown-toggle btn btn-sm" aria-expanded="false">
                                                <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                                            </button>

                                            <div class="dropdown-menu w-48 z-10">
                                                <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                                    <a
                                                        href="javascript:void(0);"
                                                        data-tw-toggle="modal"
                                                        data-tw-target="#transaction-detail-modal"
                                                        onclick="Livewire.emit('showTransactionDetail', {{ $transaction->id }})"
                                                        class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"
                                                    >
                                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Show
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                    <div class="my-2">
                        {{-- {{ $transactions->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="transaction-detail-modal" class="modal modal-slide-over z-50" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">Transaction Details</h2>
                </div>

                <div class="modal-body">
                    <livewire:transaction-detail />
                </div>
            </div>
        </div>
    </div>
@endsection
