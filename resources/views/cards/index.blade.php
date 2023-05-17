@extends('cms::dashboard.layouts.default')

@section('title', 'Cards')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Cards
                    </h2>
                    @can(\Kanexy\Banking\Policies\CardPolicy::CREATE, \Kanexy\Banking\Models\Card::class)
                        <a href="{{ route('dashboard.cards.create', ['workspace_id' => $workspace?->id]) }}"
                        class="btn btn-sm btn-primary shadow-md sm:ml-1 sm:-mt-2 sm:mb-0 mb-2 py-2">Request
                        New Card</a>
                    @endcan
                </div>
                
                <div class="Livewire-datatable-modal pb-3">
                    <livewire:data-table model='Kanexy\Banking\Models\Card' params="{{ $workspace?->id }}" type='cards' />
                </div>
            </div>
        </div>
    </div>
    <div id="card-close-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">Card Close Details</h2>
                </div>
                <div class="modal-body">
                    <livewire:card-close-detail />

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.addEventListener('show-card-close-modal', event => {
            const myModal = tailwind.Modal.getInstance(document.querySelector(
                "#card-close-modal"));
            myModal.show();
        });
        window.addEventListener('close-modal', event => {
            const myModal = tailwind.Modal.getInstance(document.querySelector(
                "#card-close-modal"));
            myModal.hide();
        })
    </script>
@endpush
