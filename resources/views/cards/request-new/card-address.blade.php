@extends('banking::cards.request-new.wizard-skeleton')

@section('card-content')
    <div class="px-5 sm:px-20 mt-5 pt-5 border-t border-gray-200 ">
        <form method="POST" action="{{ route('dashboard.cards.store-card-address') }}">
            @csrf

            <input type="hidden" name="workspace_id" value="{{ request()->input('workspace_id') }}">

            <div class="intro-y col-span-12 lg:col-span-12">
                <div class="intro-y box mb-5">
                    <div class="flex sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base">
                            Shipping Address
                        </h2>

                        <div id="ShippingAddressCreate" class="text-right  ml-auto">
                            <a id="CreateNew" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#card-shipping-address-modal" class="btn btn-primary">Create New</a>
                        </div>
                    </div>
                    @error('shipping_address_id')
                        <span class="block text-theme-6 mt-2 break-normal">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-12 gap-4 mt-3">
                    @foreach ($shippingAddresses as $index => $shippingAddress)
                        <div class="col-span-12 lg:col-span-4">
                            <div class="box w-full bg-gray-200 form-check h-full">
                                <input id="card-shipping-address-{{ $shippingAddress->id }}" class="form-check-input ml-5"
                                    type="radio" name="shipping_address_id" value="{{ $shippingAddress->id }}"
                                    @if ($index === 0) checked @endif>

                                <label class="form-check-label flex-grow ml-5 p-5"
                                    for="card-shipping-address-{{ $shippingAddress->id }}">
                                    <div class="flex flex-col lg:flex-row justify-between items-center">
                                        <div class="lg:ml-2 lg:mr-auto sm:text-center lg:text-left mt-3 lg:mt-0">
                                            <p class="mb-1">
                                                {{ $shippingAddress->house_no ? $shippingAddress->house_no . ', ' : '' }}
                                                {{ $shippingAddress->street }}, {{ $shippingAddress->address_info ?? '' }}
                                                <strong>({{ $shippingAddress->postcode }})</strong>
                                            </p>

                                            <p class="font-medium text-md">{{ $shippingAddress->city }},
                                                {{ $shippingAddress->county }}, {{ $shippingAddress->country->name }}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="intro-y box mt-5 mb-5">
                    <div class="flex sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                        <h2 class="font-medium text-base">
                            Billing Address
                        </h2>

                        <div class="text-right ml-auto">
                            <a id="BillingAddressCreate" href="javascript:;" data-tw-toggle="modal"
                                data-tw-target="#card-billing-address-modal" class="btn btn-primary">Create New</a>
                        </div>
                    </div>
                    @error('billing_address_id')
                        <span class="block text-theme-6 mt-2 break-normal">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-12 gap-4 mt-3">
                    @foreach ($billingAddresses as $index => $billingAddress)
                        <div class="col-span-12 lg:col-span-4">
                            <div class="box w-full bg-gray-200 form-check h-full">
                                <input id="card-billing-address-{{ $billingAddress->id }}" class="form-check-input ml-5"
                                    type="radio" name="billing_address_id" value="{{ $billingAddress->id }}"
                                    @if ($index === 0) checked @endif>

                                <label class="form-check-label flex-grow ml-5 p-5"
                                    for="card-billing-address-{{ $billingAddress->id }}">
                                    <div class="flex flex-col lg:flex-row justify-between items-center">
                                        <div class="lg:ml-2 lg:mr-auto sm:text-center lg:text-left mt-3 lg:mt-0">
                                            <p class="mb-1">
                                                {{ $billingAddress->house_no ? $billingAddress->house_no . ', ' : '' }}
                                                {{ $billingAddress->street }}, {{ $billingAddress->address_info ?? '' }}
                                                <strong>({{ $billingAddress->postcode }})</strong>
                                            </p>

                                            <p class="font-medium text-md">{{ $billingAddress->city }},
                                                {{ $billingAddress->county }}, {{ $billingAddress->country->name }}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-right mt-5 form-inline text-right mt-10 float-right">
                <a id="CardPrevious"
                    href="{{ route('dashboard.cards.show-card-mode', ['workspace_id' => session()->get('card_request.workspace_id')]) }}"
                    class="btn btn-secondary w-24 inline-block mr-2">Previous</a>
                <button id="CardNext" type="submit" class="btn btn-primary w-24">Next</button>
            </div>
        </form>

        @include('banking::cards.card-address-modal', [
            'addressType' => 'billing',
            'workspace' => $workspace,
        ])
        @include('banking::cards.card-address-modal', [
            'addressType' => 'shipping',
            'workspace' => $workspace,
        ])
    </div>
@endsection
