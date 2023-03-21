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
