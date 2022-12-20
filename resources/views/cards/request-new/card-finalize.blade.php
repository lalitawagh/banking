@extends("partner-foundation::cards.request-new.wizard-skeleton")

@section('card-content')
    <div class="px-5 sm:mx-10 md:mx-5 lg:mx-20 mt-10 pt-10 border-t border-gray-200">
        <form action="{{ route('dashboard.cards.finalize-card') }}" method="POST">
            @csrf

            <input type="hidden" name="workspace_id" value="{{ request()->input('workspace_id') }}">

            <div class="grid grid-cols-12 lg:gap-10 sm:gap-3">
                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_holder_name" class="form-label sm:w-40">Card Holder Name <span class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                    <input
                        id="card_holder_name"
                        name="card_holder_name"
                        type="text"
                        class="form-control"
                        value="{{ $requestCard['card_holder_name'] }}"
                        disabled
                    >
                    </div>
                </div>

                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_type" class="form-label sm:w-40 ">Card Type <span class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                    <input
                        id="card_type"
                        name="card_type"
                        type="text"
                        class="form-control capitalize"
                        value="{{ $requestCard['card_type'] }}"
                        disabled
                    >
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 lg:gap-10 sm:gap-3 mt-3">
                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_mode" class="form-label sm:w-40">Card Mode <span class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                    <input
                        id="card_mode"
                        name="card_mode"
                        type="text"
                        class="form-control capitalize"
                        value="{{ $requestCard['mode'] }}"
                        disabled
                    >
                </div>
                </div>
            </div>

            <div class="grid grid-cols-12 lg:gap-10 sm:gap-3 mt-3">
                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_billing_address" class="form-label sm:w-40">Billing Address </label>
                    <div class="sm:w-5/6">
                    <input
                        id="card_billing_address"
                        name="card_billing_address"
                        type="text"
                        class="form-control"
                        value="{{ $cardBillingAddress?->toString() }}"
                        disabled
                    >
                    </div>

                </div>

                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_shipping_address" class="form-label sm:w-40">Shipping Address </label>
                    <div class="sm:w-5/6">
                    <input
                        id="card_shipping_address"
                        name="card_shipping_address"
                        type="text"
                        class="form-control"
                        value="{{ $cardDeliveryAddress?->toString() }}"
                        disabled
                    >
                    </div>
                </div>
            </div>

            <div class="text-right mt-5 form-inline text-right mt-5 float-right">
                <a href="{{ route('dashboard.cards.index', ['filter' => ['workspace_id' => \Kanexy\PartnerFoundation\Core\Helper::activeWorkspaceId()]]) }}" class="btn btn-secondary w-24 inline-block mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary w-24">Process</button>
            </div>
        </form>
    </div>
@endsection
