<div class="p-0">
    @foreach ($requestQueries as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    @if ($sent_resend_otp == true)
        <h4 class="text-theme-9 mb-2">OTP Resend Successfully</h4>
    @endif
    <div class="grid grid-cols-12 md:gap-3 mt-0">
        @if(Kanexy\Cms\Setting\Models\Setting::getValue('transaction_otp_service') == 'sms')
            <div class="col-span-12 md:col-span-12 form-inline">
                <label for="mobile" class="form-label sm:w-16">Mobile</label>
                <div class="sm:w-5/6 tillselect-marging">
                    <div class="input-group flex flex-col sm:flex-row mb-0 mt-0">
                        <div id="input-group-phone" wire:ignore class="input-group-text flex form-inline"
                            style="padding: 0 5px;">
                            <span id="countryWithPhoneFlagImgOtp"
                                style="display: flex;justify-content: center;
                                            align-items: center;
                                            align-self: center;margin-right:10px;">
                                @foreach ($countries as $country)
                                    @if ($country->id == old('country_code', $defaultCountry))
                                        <img src="{{ $country->flag }}">
                                    @endif
                                @endforeach
                            </span>
                            <select id="countryWithPhone" name="country_code" onchange="getFlagImg(this,'Otp')"
                                data-search="true" class="tail-select" style="width:20%">
                                @foreach ($countries as $country)
                                    <option data-source="{{ $country->flag }}" value="{{ $country->id }}"
                                        @if ($country->id == old('country_code', $defaultCountry)) selected @else @endif>
                                        {{ $country->code }} ({{ $country->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input name="mobile" value="{{ auth()->user()->phone }}" type="number"
                            class="form-control @error('phone') border-theme-6 @enderror"
                            onKeyPress="if(this.value.length==11) return false;return onlyNumberKey(event);" disabled>
                    </div>
                </div>
            </div>
        @else
            <div class="col-span-12 md:col-span-12 form-inline">
                <label for="code" class="form-label sm:w-16">Email <span class="text-theme-6">*</span></label>
                <div class="sm:w-5/6">
                    <input name="email" value="{{ auth()->user()->email }}" type="text"
                    class="form-control @error('email') border-theme-6 @enderror"disabled>
                </div>
            </div>
        @endif

        <div class="col-span-12 md:col-span-12 form-inline">
            <label for="code" class="form-label sm:w-16">OTP <span class="text-theme-6">*</span></label>
            <div class="sm:w-5/6">
                <input id="code" wire:model.defer="code" name="code" type="text" class="form-control"
                    required="required">
                @error('code')
                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                @enderror
                <a href="#" wire:click="resendOtp" class="block text-theme-1 mt-2">Resend OTP</a>
            </div>
        </div>
    </div>
    <div class="text-right mt-5">
        <button id="Verify" wire:click="verifyOtp" type="button" class="btn btn-primary w-24">Verify</button>
    </div>
</div>
