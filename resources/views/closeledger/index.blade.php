@extends('cms::dashboard.layouts.default')

@section('title', 'Close Ledger Request')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Close Ledger Request
                    </h2>
                    @if ($user->isSubscriber())
                        <div class="intro-y mt-0">
                            <div
                                class="text-right flex-wrap sm:flex items-center justify-end sm:py-0 border-b border-gray-200 dark:border-dark-5">
                                @can(\Kanexy\Banking\Policies\CloseLedgerPolicy::CREATE,
                                    \Kanexy\PartnerFoundation\Core\Models\ArchivedMember::class)
                                    <a id="closeLedger" href="#" data-tw-toggle="modal" data-tw-target="#close-ledger-modal"
                                        class="btn btn-sm btn-primary sm:ml-2 py-2 sm:mb-2 mb-2">
                                        New Request
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-5 py-3">

                    <div class="overflow-x-auto overflow-y-hidden">
                        <table class="shroting display table table-report -mt-2">
                            <thead class="short-wrp dark:bg-darkmode-400 dark:border-darkmode-400">
                                <tr>
                                    <th class="whitespace-nowrap text-left">#</th>
                                    <th class="whitespace-nowrap text-left">Name
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
                                    <th class="whitespace-nowrap text-left">Email Address
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
                                    <th class="whitespace-nowrap text-left">Mobile No.
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
                                    <th class="whitespace-nowrap text-left">Bank Sort Code
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
                                    <th class="whitespace-nowrap text-left">Bank Account No.
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
                                    <th class="whitespace-nowrap text-left">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($closeLedgerRequests as $index => $closeLedgerRequest)
                                    <tr @if ($index % 2 === 0) class="bg-gray-200 dark:bg-dark-1" @endif>
                                        <td class="whitespace-nowrap text-left">
                                            {{ $index + $closeLedgerRequests->firstItem() }}</td>
                                        <td class="whitespace-nowrap text-left">{{ $closeLedgerRequest->meta['name'] }}
                                        </td>
                                        <td class="whitespace-nowrap text-left">{{ $closeLedgerRequest->meta['email'] }}
                                        </td>
                                        <td class="whitespace-nowrap text-left">{{ $closeLedgerRequest->meta['phone'] }}
                                        </td>
                                        <td class="whitespace-nowrap text-left">
                                            {{ @$closeLedgerRequest->meta['bank_code'] }}</td>
                                        <td class="whitespace-nowrap text-left">
                                            {{ @$closeLedgerRequest->meta['account_number'] }}</td>

                                        <td class="whitespace-nowrap text-left capitalize">
                                            {{ ucwords($closeLedgerRequest->status) }}
                                        </td>
                                        <td class="whitespace-nowrap text-left">
                                            <div class="dropdown">
                                                <button id="Setting" class="dropdown-toggle btn px-2 box"
                                                    aria-expanded="false" data-tw-toggle="dropdown">
                                                    <span class="w-5 h-5 flex items-center justify-center">
                                                        <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                                                    </span>
                                                </button>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        @if ($closeLedgerRequest->status == \Kanexy\Cms\Enums\Status::PENDING)
                                                            @can(\Kanexy\Banking\Policies\CloseLedgerPolicy::EDIT,
                                                                \Kanexy\PartnerFoundation\Core\Models\ArchivedMember::class)
                                                                <li>
                                                                    <a id="Approve"
                                                                        href="{{ route('dashboard.banking.closeledger.approveRequest', $closeLedgerRequest->getKey()) }}"
                                                                        class="flex items-center block dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                        <x-feathericon-check class="w-4 h-4 mr-1" />
                                                                        Approve
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a id="Decline"
                                                                        href="{{ route('dashboard.banking.closeledger.declineRequest', $closeLedgerRequest->getKey()) }}"
                                                                        class="flex items-center block dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                        <x-feathericon-x class="w-4 h-4 mr-1" />
                                                                        Decline
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                        @endif
                                                        @can(\Kanexy\Banking\Policies\CloseLedgerPolicy::VIEW,
                                                            \Kanexy\PartnerFoundation\Core\Models\ArchivedMember::class)
                                                            <li>
                                                                <a id="show" href="#" data-tw-toggle="modal"
                                                                    data-tw-target="#show-close-ledger"
                                                                    onclick="Livewire.emit('show', {{ $closeLedgerRequest->getKey() }});"
                                                                    class="flex items-center block dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                    <x-feathericon-eye class="w-4 h-4 mr-1" />
                                                                    show
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                    <div class="my-2">
                        {{ $closeLedgerRequests->links() }}
                    </div>
                    <div class="Livewire-datatable-modal pb-3">
                        <livewire:data-table model='Kanexy\PartnerFoundation\Core\Models\ArchivedMember' params="{{ $workspace?->id }}" type="closeledger" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="show-close-ledger" class="modal modal-slide-over" data-tw-backdrop="static" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- BEGIN: Slide Over Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Show Close Account Request</h2>

                    <a data-tw-dismiss="modal" href="javascript:;">
                        <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                    <div class="dropdown sm:hidden">
                        <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false"
                            data-tw-toggle="dropdown">
                            <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i>
                        </a>
                    </div>
                </div>
                <!-- END: Slide Over Header -->

                <div class="modal-body">
                    @livewire('show-close-ledger')
                </div>
            </div>
        </div>
    </div>

    @if ($user->isSubscriber())
        @livewire('close-ledger-modal')
    @endif
@endsection
@push('scripts')
    <script>
        window.addEventListener('show', event => {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#show-close-ledger"));
            myModal.show();
        });
    </script>
@endpush
