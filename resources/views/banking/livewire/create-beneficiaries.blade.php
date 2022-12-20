<div class="p-0">
    <form>
        <input name="callback_url" type="hidden" value="{{ request()->query('callback_url') }}">
        <div class="grid grid-cols-12 lg:gap-10 mt-0 mb-3">
            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                <label class="form-label sm:w-30">Contact Type <span class="text-theme-6">*</span></label>
                <div class="sm:w-5/6 sm:pt-1">
                    <div class="flex sm:flex-row mt-2">
                        <div class="form-check mr-6">
                            <input wire:model="type" id="type-personal" class="form-check-input contact-type"
                                type="radio" name="type" value="personal">
                            <label class="form-check-label" for="type-personal">Personal</label>
                        </div>

                        <div class="form-check mr-2 sm:mt-0">
                            <input wire:model="type" id="type-company" class="form-check-input contact-type"
                                type="radio" name="type" value="company">
                            <label class="form-check-label" for="type-company">Company</label>
                        </div>
                    </div>

                    @error('type')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="ihphone-scroll-height-inr">
            <div class="grid grid-cols-12 lg:gap-10 mt-0 contact-personal">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="first_name" class="form-label sm:w-30">First Name <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="first_name" id="first_name" name="first_name" type="text"
                            class="form-control @error('first_name') border-theme-6 @enderror"
                            value="{{ old('first_name') }}" required>

                        @error('first_name')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2 contact-personal">
                    <label for="middle_name" class="form-label sm:w-30">Middle Name</label>
                    <div class="sm:w-5/6">
                        <input wire:model="middle_name" id="middle_name" name="middle_name" type="text"
                            class="form-control @error('middle_name') border-theme-6 @enderror"
                            value="{{ old('middle_name') }}">

                        @error('middle_name')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 lg:gap-10 mt-0">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2 contact-personal">
                    <label for="last_name" class="form-label sm:w-30">Last Name <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="last_name" id="last_name" name="last_name" type="text"
                            class="form-control @error('last_name') border-theme-6 @enderror"
                            value="{{ old('last_name') }}" required>

                        @error('last_name')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2 contact-company ">
                    <label for="company_name" class="form-label sm:w-30">Company Name <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="company_name" id="company_name" name="company_name"
                            type="text" class="form-control @error('company_name') border-theme-6 @enderror"
                            value="{{ old('company_name') }}">

                        @error('company_name')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2 ">
                    <label for="email" class="form-label sm:w-30">Email Address</label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="email" id="email" name="email" type="email"
                            class="form-control @error('email') border-theme-6 @enderror" value="{{ old('email') }}">

                        @error('email')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 lg:gap-10 mt-0">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="landline" class="form-label sm:w-30">Landline No.</label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="landline" id="landline" name="landline" type="text"
                            class="form-control @error('landline') border-theme-6 @enderror"
                            value="{{ old('landline') }}">

                        @error('landline')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="mobile" class="form-label sm:w-30">Mobile No.</label>
                    <div class="sm:w-5/6 tillselect-marging">
                        <div class="input-group flex flex-col sm:flex-row mb-0 mt-0">
                            <div id="input-group-phone" wire:ignore class="input-group-text flex form-inline"
                                style="padding: 0 5px;">

                                <span id="countryWithPhoneFlagImgTransferOtp"
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
                            <input name="mobile" wire:model.debounce.500ms="mobile" type="number"
                                class="form-control @error('phone') border-theme-6 @enderror"
                                onKeyPress="if(this.value.length==11) return false;return onlyNumberKey(event);">
                        </div>
                        @error('mobile')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-12 lg:gap-10 mt-0">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="bank_account_name" class="form-label sm:w-30"> Account Name <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="meta.bank_account_name" id="bank_account_name"
                            name="meta[bank_account_name]" type="text"
                            class="form-control @error('meta.bank_account_name') border-theme-6 @enderror"
                            value="{{ old('meta.bank_account_name') }}" required>

                        @error('meta.bank_account_name')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="bank_account_number" class="form-label sm:w-30"> Account No. <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="meta.bank_account_number" id="bank_account_number"
                            name="meta[bank_account_number]" type="text"
                            class="form-control @error('meta.bank_account_number') border-theme-6 @enderror"
                            value="{{ old('meta.bank_account_number') }}" required>

                        @error('meta.bank_account_number')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 lg:gap-10 mt-0">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="bank_code" class="form-label sm:w-30"> Sort Code <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input wire:model.debounce.500ms="meta.bank_code" id="bank_code" name="meta[bank_code]"
                            type="text" class="form-control @error('meta.bank_code') border-theme-6 @enderror"
                            value="{{ old('meta.bank_code') }}" required>

                        @error('meta.bank_code')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="avatar" class="form-label sm:w-30">Avatar</label>
                    <div class="sm:w-5/6">
                        <input wire:model="avatar" id="avatar" name="avatar" type="file"
                            class="form-control @error('avatar') border-theme-6 @enderror"
                            value="{{ old('avatar') }}">
                        @error('avatar')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 lg:gap-10 mt-0">
                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                    <label for="bank_country" class="form-label sm:w-30"> Country <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6 tillselect-marging">
                        <select wire:model="meta.bank_country" id="bank_country" name="meta[bank_country]"
                            data-search="true"
                            class="form-control @error('meta.bank_country') border-theme-6 @enderror">
                            @foreach ($countries as $country)
                                <option value="{{ $country->getKey() }}"
                                    @if ($country->id == $defaultCountry) selected @else @endif>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('meta.bank_country')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-5">
            <a id="CancelBeneficiary"
                href="{{ route('dashboard.banking.beneficiaries.index', ['filter' => ['workspace_id' => $workspace?->id]]) }}"
                class="btn btn-secondary w-24 inline-block mr-1">Cancel</a>
            <button wire:click="createBenificiary" type="button" class="btn btn-primary w-24">
                <div id="CreateBeneficiary" wire:loading.remove wire:target="createBenificiary">
                    Create
                </div>
                <div wire:loading.flex wire:target="createBenificiary">
                    Processing
                </div>
            </button>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        //script for hiding fields dynamically based on type is on custom.js file in project root directory check that
        window.addEventListener('setBeneficiaryType', event => {
            contactTypeChange(event.detail.type)
        })
    </script>
@endpush
