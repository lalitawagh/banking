@extends('cms::dashboard.layouts.default')

@section('title', 'Send Money')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center p-3 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto sm:mb-0 mb-2">
                        Send Money
                    </h2>
                    <div class="ml-auto pos">
                        <ul class="nav nav-pills w-7/5 bg-slate-100 dark:bg-black/20 rounded-md mx-auto" role="tablist">
                            <li id="local-tab" class="nav-item flex-1" role="presentation">
                                <a href="{{ route('dashboard.banking.payouts.index', ['workspace_id' => \Kanexy\PartnerFoundation\Core\Helper::activeWorkspaceId()]) }}"
                                   class="nav-link w-full py-1.5 px-2 active" data-tw-toggle="pill" data-tw-target="#local"
                                   type="button" role="tab" aria-controls="local" aria-selected="true">
                                    Local
                                </a>
                            </li>
                            @foreach (\Kanexy\PartnerFoundation\Core\Facades\BankingProcessSelectionComponent::getItems() as $item)
                                {!! $item->render() !!}
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="p-5">
                    <form id="payout-form" action="{{ route('dashboard.banking.payouts.store', $workspace->id) }}"
                          method="POST" enctype="multipart/form-data" x-data="createPayoutForm()">
                        @csrf

                        <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">

                        @error('feature')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                        <br>

                        <div class="grid grid-cols-12 md:gap-3 lg:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="sender_account_id" class="form-label sm:w-24">Account No. <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6 tillselect-marging">
                                    <select id="sender_account_id" name="sender_account_id"
                                            class="@if ($errors->has('sender_account_id')) border-theme-6 @endif" data-search="true"
                                            x-ref="activeAccountDropdownRef" x-on:change="changeActiveAccount($el)">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->bank_code }} /
                                                {{ $account->account_number }}</option>
                                        @endforeach
                                    </select>

                                    @error('sender_account_id')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="balance" class="form-label sm:w-24">Balance</label>
                                <div class="sm:w-5/6">
                                    <input id="balance" type="text" class="form-control"
                                           x-bind:value="activeAccountBalance" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-3 lg:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="beneficiary_id" class="form-label sm:w-24">Beneficiary <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6 relative sm:ml-0 tillselect-marging">
                                    <select id="beneficiary_id" name="beneficiary_id"
                                            class="@if ($errors->has('beneficiary_id')) border-theme-6 @endif" data-search="true"
                                            x-ref="activeBeneficiaryDropdownRef" x-on:change="changeActiveBeneficiary($el)">
                                        @foreach ($beneficiaries as $beneficiary)
                                            <option value="{{ $beneficiary->id }}">{{ $beneficiary->getFullName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <template x-if="activeBeneficiary">
                                        <div class="mt-2">
                                            <span class="text-gray-600">Bank Account: </span>
                                            <span class="font-medium "
                                                  x-text="`${activeBeneficiary.meta['bank_code']} / ${activeBeneficiary.meta['bank_account_number']}`"></span>
                                        </div>
                                    </template>

                                    @error('beneficiary_id')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror

                                    {{-- new button for overlay --}}
                                    <a href="#" id="create-beneficiaries-btn" data-tw-toggle="modal"
                                       data-tw-target="#create-beneficiaries-modal" class="absolute top-0 right-0 plus"
                                       style="right: -20px;top: 0;margin-top: 19px;">
                                        <i data-lucide="plus-circle" class="w-4 h-4 ml-4"></i>

                                    </a>
                                    {{-- end of the new button overlay --}}

                                    {{-- new button for otp verfication --}}
                                    <a id="otp-verification-btn" data-tw-toggle="modal"
                                       data-tw-target="#otp-verification-modal" class="hidden absolute top-0 right-0 plus"
                                       style="right: -20px;top: 0;margin-top: 19px;">
                                        <i data-lucide="plus-circle" class="w-4 h-4 ml-4"></i>
                                    </a>
                                    {{-- end of the new button overlay --}}

                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="remaining" class="form-label sm:w-24">Remaining</label>
                                <div class="sm:w-5/6">
                                    <input id="remaining" type="text" class="form-control"
                                           x-bind:value="remainingAccountBalance" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-3 lg:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="amount" class="form-label sm:w-24">Amount <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="amount" name="amount" type="text"
                                           class="form-control @error('amount') border-theme-6 @enderror"
                                           value="{{ old('amount') }}" x-on:change="changeTransactionAmount($el)" required>

                                    @error('amount')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="reference" class="form-label sm:w-24">Reference</label>
                                <div class="sm:w-5/6">
                                    <input id="reference" name="reference" type="text"
                                           class="form-control @error('reference') border-theme-6 @enderror"
                                           value="{{ old('reference') }}">

                                    @error('reference')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-3 lg:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="note" class="form-label sm:w-24">Note</label>
                                <div class="sm:w-5/6">
                                    <input id="note" name="note" type="text"
                                           class="form-control @error('note') border-theme-6 @enderror"
                                           value="{{ old('note') }}">

                                    @error('note')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="attachment" class="form-label sm:w-24">Attachment</label>
                                <div class="sm:w-5/6">
                                    <input id="attachment" name="attachment" type="file"
                                           class="form-control @error('attachment') border-theme-6 @enderror">

                                    @error('attachment')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-5">
                            <a id="payoutCancel"
                               href="{{ route('dashboard.banking.transactions.index', ['filter' => ['workspace_id' => \Kanexy\PartnerFoundation\Core\Helper::activeWorkspaceId()]]) }}"
                               class="btn btn-secondary w-24 inline-block mr-1">Cancel</a>
                            <button id="payoutProcess" type="submit" class="btn btn-primary w-24">Process</button>
                        </div>
                    </form>
                </div>

                @includeWhen($workspace->status == \Kanexy\PartnerFoundation\Workspace\Enums\WorkspaceStatus::INACTIVE,
                    'partner-foundation::core.inactive-account-alert')
            </div>
        </div>
    </div>

    <!-- BEGIN: Create Beneficiaries Modal -->
    <div id="create-beneficiaries-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl ihphone-scroll-height">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">
                        Beneficiaries
                    </h2>
                    <div class="items-center justify-center mt-0">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="p-0">
                        @livewire('create-beneficiaries', ['workspaceId' => request()->workspace_id, 'countries' => $countries, 'defaultCountry' => $defaultCountry])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Beneficiaries Modal -->


    <!-- BEGIN: OTP verification Modal -->
    <div id="otp-verification-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h2 class="font-medium text-base mr-auto">
                        Verify OTP
                    </h2>
                    <div class="items-center justify-center mt-0">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="p-0">
                        @livewire('otp-verification', ['countries' => $countries, 'defaultCountry' => $defaultCountry, 'workspaceId' => request()->workspace_id])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Create Beneficiaries Modal -->

@endsection

@push('scripts')
    <script>
        function contactTypeChange(val) {

            if (val == "{{ \Kanexy\PartnerFoundation\Cxrm\Enums\ContactClassificationType::COMPANY }}") {
                $(".contact-company").removeClass('hidden hiddenform');
                $(".contact-company").addClass('visible');
                $(".contact-company input").attr('required', 'required');
                $("#first_name, #middle_name, #last_name").val('');

                $(".contact-personal").removeClass('visible');
                $(".contact-personal").addClass('hidden hiddenform');
                $(".contact-personal input").removeAttr('required');
            } else {
                $(".contact-company").removeClass('visible');
                $(".contact-company").addClass('hidden hiddenform');
                $(".contact-company input").removeAttr('required');

                $(".contact-personal").removeClass('hidden hiddenform');
                $(".contact-personal").addClass('visible');
                $(".contact-personal input").attr('required', 'required');
                $(".contact-personal #middle_name").removeAttr('required');
                $("#company_name").val('');
            }
        }
        $(".contact-type").each(function() {
            if ($(this).is(':checked')) {
                contactTypeChange($(this).val());
            }
        });
        $(".contact-type").click(function() {
            contactTypeChange($(this).val());
        });

        contactTypeChange("{{ \Kanexy\PartnerFoundation\Cxrm\Enums\ContactClassificationType::PERSONAL }}")
    </script>
@endpush

@push('scripts')
    <script>
        window.addEventListener('openOverLay', event => {
            document.getElementById("create-beneficiaries-modal").click();
            document.getElementById("" + event.detail.overlayName + "").click();
        })
    </script>
@endpush

@push('scripts')
    <script>
        function createPayoutForm() {
            return {
                accounts: {!! $accounts->toJson() !!},
                activeAccountId: '',
                activeBeneficiaryId: '',
                beneficiaries: {!! $beneficiaries->toJson() !!},
                transactionAmount: 0,

                get activeAccount() {
                    return this.accounts.find(account => account.id == this.activeAccountId);
                },

                get activeBeneficiary() {
                    return this.beneficiaries.find(beneficiary => beneficiary.id == this.activeBeneficiaryId);
                },

                get activeAccountBalance() {
                    if (!this.activeAccount) return 0.00;

                    return this.activeAccount.balance.toFixed(2);
                },

                get remainingAccountBalance() {
                    if (!this.activeAccount) return 0.00;

                    var remainingBalance = this.activeAccount.balance - this.transactionAmount;

                    return (remainingBalance).toFixed(2);
                },

                init() {
                    this.changeActiveAccount(this.$refs.activeAccountDropdownRef);
                    this.changeActiveBeneficiary(this.$refs.activeBeneficiaryDropdownRef);
                },

                changeActiveAccount($el) {
                    this.activeAccountId = $el.value;
                },

                changeActiveBeneficiary($el) {
                    this.activeBeneficiaryId = $el.value;
                },

                changeTransactionAmount($el) {
                    this.transactionAmount = new Number($el.value);
                },
            };
        }
    </script>
@endpush

@push('scripts')
    <script>
        function getFlagImg(the, type) {
            var img = $('option:selected', the).attr('data-source');
            $('#countryWithPhoneFlagImgTransfer' + type).html('<img src="' + img + '">');
        }

        function getFlagImgLandline(the, type) {
            var img = $('option:selected', the).attr('data-source');
            $('#countryWithLandlineFlagImgTransfer' + type).html('<img src="' + img + '">');
        }
    </script>
@endpush
