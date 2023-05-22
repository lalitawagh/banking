<div id="close-ledger-modal" class="modal modal-slide-over" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl ihphone-scroll-height">
        <div class="modal-content">
            <!-- BEGIN: Slide Over Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Close Account Request</h2>

                <a data-tw-dismiss="modal" href="javascript:;">
                    <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
                <div class="dropdown hidden">
                    <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false"
                        data-tw-toggle="dropdown">
                        <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i>
                    </a>
                </div>
            </div>
            <!-- END: Slide Over Header -->

            <form action="{{ route('dashboard.banking.closeledger.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="grid grid-cols-12 md:gap-0 mt-0 ihphone-scroll-height-inr1">
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="email" class="form-label sm:w-40">Email <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="email" name="email" value="{{ old('email', $user?->email) }}"
                                    type="email" class="form-control  @error('email') border-theme-6 @enderror"
                                    readonly>
                                @error('email')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="amount" class="form-label sm:w-40"> Mobile <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6 tillselect-marging">
                                <div class="input-group flex flex-col sm:flex-row">
                                    <div id="input-group-phone" wire:ignore class="input-group-text flex form-inline"
                                        style="padding: 0 5px;">

                                        <span id="countryWithPhoneFlagImg"
                                            style="display: flex;
                                                    justify-content: center;
                                                    align-items: center;
                                                    align-self: center;margin-right:10px;">
                                            @foreach ($countryWithFlags as $country)
                                                @if ($country->id == old('country_code', $user->country_id))
                                                    <img src="{{ $country->flag }}">
                                                @endif
                                            @endforeach
                                        </span>

                                        <select id="countryWithPhone" name="country_code" class="w-full form-control">
                                            @foreach ($countryWithFlags as $country)
                                                <option data-source="{{ $country->flag }}" value="{{ $country->id }}"
                                                    @if ($country->id == old('country_code', $user->country_id)) selected @endif>
                                                    {{ $country->code }} ({{ $country->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input id="phone" name="phone" value="{{ old('phone', $user?->phone) }}"
                                        type="number" class="form-control @error('phone') border-theme-6 @enderror"
                                        onKeyPress="if(this.value.length==11) return false;return onlyNumberKey(event);"
                                        readonly>

                                </div>

                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="name" class="form-label sm:w-40">Name <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="name" name="name" value="{{ $account->name }}" type="text"
                                    class="form-control  @error('name') border-theme-6 @enderror" readonly>
                                @error('name')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="bank_code" class="form-label sm:w-40">Sort Code <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="bank_code" name="bank_code" value="{{ $account->bank_code }}" type="text"
                                    class="form-control  @error('bank_code') border-theme-6 @enderror" readonly>
                                @error('bank_code')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="account_number" class="form-label sm:w-40">Account Number <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="account_number" name="account_number" value="{{ $account->account_number }}"
                                    type="text"
                                    class="form-control  @error('account_number') border-theme-6 @enderror" readonly>
                                @error('account_number')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="iban_number" class="form-label sm:w-40">IBAN <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="iban_number" name="iban_number" value="{{ $account->iban_number }}"
                                    type="text" class="form-control  @error('iban_number') border-theme-6 @enderror"
                                    readonly>
                                @error('iban_number')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="bic_swift" class="form-label sm:w-40">BIC/SWIFT Code <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="bic_swift" name="bic_swift" value="{{ $account->bic_swift }}"
                                    type="text" class="form-control  @error('bic_swift') border-theme-6 @enderror"
                                    readonly>
                                @error('bic_swift')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-12 lg:col-span-12 sm:col-span-12 form-inline mt-2">
                            <label for="reason" class="form-label sm:w-40">Close Reason<span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <textarea class="w-full" id="reason" name="reason" rows="3" cols="20" required></textarea>

                                @error('reason')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Slide Over Body -->
                <!-- BEGIN: Slide Over Footer -->
                <div class="modal-footer
                            w-full bottom-0">
                    <button id="LedgerCancel" type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button id="LedgerSubmit" type="submit" class="btn btn-primary w-20">Submit</button>
                </div>
                <!-- END: Slide Over Footer -->
            </form>
            <!-- BEGIN: Slide Over Body -->
        </div>
    </div>
</div>
<!-- END: Slide Over Content -->
