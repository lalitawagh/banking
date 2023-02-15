<div class="grid grid-cols-12 md:gap-0 mt-0 ihphone-scroll-height-inr3">
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="email" class="form-label sm:w-40">Email <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="email" name="email" value="{{ $details->meta['email'] ?? '' }}" type="email"
                class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="amount" class="form-label sm:w-40"> Mobile <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6 tillselect-marging">
            <div class="input-group flex flex-col sm:flex-row">
                <div id="input-group-phone" wire:ignore class="input-group-text flex form-inline"
                    style="padding: 0 5px;">

                    <span id="countryWithPhoneFlagImg"
                        style="display: flex;justify-content: center;align-items: center;align-self: center;margin-right:10px;">
                        @foreach ($countryWithFlags as $country)
                            @if ($country->id == $country_id)
                                <img src="{{ $country->flag }}">
                            @endif
                        @endforeach
                    </span>
                    <select class="w-full">
                        @foreach ($countryWithFlags as $country)
                            <option data-source="{{ $country->flag }}" value="{{ $country->id }}"
                                @if ($country->id == $country_id) selected @endif>
                                {{ $country->code }} ({{ $country->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <input id="phone" name="phone" value="{{ $details->meta['phone'] ?? '' }}" type="number"
                    class="form-control" readonly>

            </div>

        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="name" class="form-label sm:w-40">Name <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="name" name="name" value="{{ $details->meta['name'] ?? '' }}" type="text"
                class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="bank_code" class="form-label sm:w-40">Sort Code <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="bank_code" name="bank_code" value="{{ $details->meta['bank_code'] ?? '' }}" type="text"
                class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="account_number" class="form-label sm:w-40">Account Number <span
                class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="account_number" name="account_number" value="{{ $details->meta['account_number'] ?? '' }}"
                type="text" class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="iban_number" class="form-label sm:w-40">IBAN <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="iban_number" name="iban_number" value="{{ $details->meta['iban_number'] ?? '' }}" type="text"
                class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="bic_swift" class="form-label sm:w-40">BIC/SWIFT Code <span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <input id="bic_swift" name="bic_swift" value="{{ $details->meta['bic_swift'] ?? '' }}" type="text"
                class="form-control" readonly>
        </div>
    </div>
    <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
        <label for="reason" class="form-label sm:w-40">Close Reason<span class="text-theme-6">*</span></label>
        <div class="sm:w-5/6">
            <textarea id="reason" name="reason" rows="3" cols="20" readonly>{{ $details->meta['reason'] ?? '' }}</textarea>

        </div>
    </div>
</div>
