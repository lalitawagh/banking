@extends('cms::dashboard.layouts.default')

@section('title', 'Transactions')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Transactions
                    </h2>
                </div>
                <div class="Livewire-datatable-modal pb-3">
                    <livewire:data-table model='Kanexy\PartnerFoundation\Core\Models\Transaction'
                        params="{{ $workspace?->id }}" type="transactions" />
                </div>
            </div>
        </div>
    </div>

    <div id="transaction-detail-modal" class="modal modal-slide-over z-50" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">Transaction Details</h2>

                    <div id="transactionDetailEdit"
                        class="edit-transaction cursor-pointer intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-14 dark:bg-dark-5 dark:text-gray-300 text-theme-10 ml-2 tooltip"
                        title="Edit"> <i data-lucide="edit" class="w-30 h-30"></i> </div>
                    <a id="transactionDetailSav"class="save-transaction cursor-pointer hidden intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-14 text-theme-10 ml-2 tooltip"
                        title="Save"> <i data-lucide="save" class="w-30 h-30"></i> </a>
                    <a id="transactionDetailClose"
                        class="close intro-x cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-theme-6 text-theme-10 ml-2 tooltip"
                        title="Close" data-tw-dismiss="modal"> <i data-lucide="x" class="w-30 h-30"></i> </a>
                    <!--<a href="" class="intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-14 dark:bg-dark-5 dark:text-gray-300 text-theme-10 ml-2 tooltip" title="Share"> <i data-lucide="share-2" class="w-3 h-3"></i> </a>
                                                        <a href="" class="intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-1 text-white ml-2 tooltip" title="Download PDF"> <i data-lucide="share" class="w-3 h-3"></i> </a>-->

                </div>

                <div class="modal-body">
                    <div class="pr-0 border-b border-gray-200 dark:border-dark-5">
                        <div class="p-0">
                            <div class="pos__tabs nav nav-tabs gap-2" role="tablist">
                                <a id="Overview-tab" data-tw-toggle="tab" data-tw-target="#Overview" href="javascript:;"
                                    class="sm:mr-8 py-2 text-center active" role="tab" aria-controls="Overview"
                                    aria-selected="true">Overview</a>
                                <a id="Attachments-tab" data-tw-toggle="tab" data-tw-target="#Attachments"
                                    href="javascript:;" class="sm:mr-8 py-2 text-center" role="tab"
                                    aria-controls="Attachments" aria-selected="false">Attachments</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content py-3">
                        <div id="Overview" class="tab-pane active " role="tabpanel" aria-labelledby="Overview-tab">
                            <div class="form-inline flex float-right">
                                <div
                                    class="edit-transaction cursor-pointer intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-14 dark:bg-dark-5 dark:text-gray-300 text-theme-10 ml-2 tooltip">
                                    <i data-feather="edit" class="w-3 h-3"></i>
                                </div>
                                <a
                                    class="save-transaction cursor-pointer intro-x w-8 h-8 flex items-center justify-center rounded-full bg-theme-1 text-white ml-2 tooltip">
                                    <i data-feather="save" class="w-3 h-3"></i> </a>
                                <a class="close intro-x cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-theme-6 text-white ml-2 tooltip"
                                    title="Close" data-dismiss="modal"> <i data-feather="x" class="w-3 h-3"></i> </a>
                            </div>
                            <div class="clearfix"></div>
                            <livewire:transaction-detail />
                        </div>

                        <div id="Attachments" class="tab-pane" role="tabpanel" aria-labelledby="Attachments-tab">
                            <livewire:transaction-attachment-component />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
@push('scripts')
    <script>
        window.addEventListener('show-transaction-detail-modal', event => {
            const mySlideOver = tailwind.Modal.getOrCreateInstance(document.querySelector(
                "#transaction-detail-modal"));
            mySlideOver.show();
            $("#loaderoverlay").css('display', 'none');
        });
        $(document).ready(function() {
            $("#table-filter-workspace_id").parent().parent().css({
                "display": "none"
            });
        });
    </script>
@endpush
