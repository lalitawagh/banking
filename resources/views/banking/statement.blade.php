@extends('cms::dashboard.layouts.default')

@section('title', 'Statement')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Statement
                    </h2>
                    <div class="intro-x dropdown ml-auto sm:mr-3">
                        <div class="dropdown-toggle notification cursor-pointer" href="#" id="select-monthyearexport-btn" role="button" type="button"
                            aria-expanded="false" data-tw-toggle="modal" data-tw-target="#select-monthyearexport-modal" onclick="Livewire.emit('statementDetail')">
                            <button class="btn btn-sm btn-primary float-right shadow-md ml-2 sm:mb-0 mb-2">Month
                                & Year Export</button>
                        </div>
                    </div>
                </div>
                <div class="Livewire-datatable-modal pb-3">
                    <livewire:data-table model='Kanexy\Banking\Models\Statement'
                        params="{{ $workspace?->id }}"  type="statements"/>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Create Beneficiaries Modal -->
    <div id="select-monthyearexport-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg ihphone-scroll-height">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">
                        Month & Year Export
                    </h2>
                    <div class="items-center justify-center mt-0">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="p-0">
                        @livewire('statementexport-pdf', ['workspace_id' => $workspace?->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Beneficiaries Modal -->

@endsection

