@extends('partner-foundation::membership.membership-layout.membership-skeleton')

@section('title', 'Membership Bank Information')

@section('membership-content')

    <div id="BankInformation"
        class="tab-pane grid grid-cols-12 gap-3 @if (Auth::user()->isSuperAdmin()) {{ request()->routeIs('dashboard.membership-bank-information', [request()->route('membershipId'), request()->route('workspaceId')]) ? 'active' : '' }} @else {{ request()->routeIs('dashboard.membership-bank-information', request()->route('workspaceId')) ? 'active' : '' }} @endif"
        role="tabpanel" aria-labelledby="BankInformation-tab">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y box mt-0">
                <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Account Information
                    </h2>
                </div>

                <div class="preview p-5">
                    <div class="grid grid-cols-12 lg:gap-10">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="account_name" class="form-label  sm:w-30 tooltip" data-theme="light"
                                title="Account Name">Account Name</label>
                            <div class="sm:w-5/6">
                                <input id="account_name" type="text" class="form-control" value="{{ $workspace?->name }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="account_number" class="form-label  sm:w-30 tooltip" data-theme="light"
                                title="Account Number">Account Number</label>
                            <div class="sm:w-5/6">
                                <input id="account_number" type="text" class="form-control"
                                    value="{{ $account?->account_number }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="sort_code" class="form-label  sm:w-30">Sort Code</label>
                            <div class="sm:w-5/6">
                                <input id="sort_code" type="text" class="form-control" value="{{ $account?->bank_code }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="iban" class="form-label  sm:w-30">Iban</label>
                            <div class="sm:w-5/6">
                                <input id="iban" type="text" class="form-control"
                                    value="{{ $account?->iban_number }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="bic_swift" class="form-label sm:w-30"> BIC/SWIFT Code</label>
                            <div class="sm:w-5/6">
                                <input id="bic_swift" type="text" class="form-control" value="{{ $account?->bic_swift }}"
                                    disabled>
                            </div>
                        </div>
                        @if (Auth::user()->isSuperAdmin())
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                <label for="ref_id" class="form-label sm:w-30"> Ref ID</label>
                                <div class="sm:w-5/6">
                                    <input id="ref_id" type="text" class="form-control"
                                        value="{{ $account?->ref_id }}" disabled>
                                </div>
                            </div>
                        @endif

                    </div>
                    @if (Auth::user()->isSuperAdmin())
                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                <label for="ref_type" class="form-label sm:w-30"> Ref Type</label>
                                <div class="sm:w-5/6">
                                    <input id="ref_type" type="text" class="form-control"
                                        value="{{ $account?->ref_type }}" disabled>
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                <label for="ledger_id" class="form-label sm:w-30">Ledger ID</label>
                                <div class="sm:w-5/6">
                                    <input id="ledger_id" type="text" class="form-control" value="{{ $account?->id }}"
                                        disabled>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                <label for="created_date" class="form-label sm:w-30 tooltip" data-theme="light"
                                    title="Created Date">Created Date</label>
                                <div class="sm:w-5/6">
                                    <input type="text" class="datepicker form-control block mx-auto"
                                        data-single-mode="true" value=" {{ $account?->created_at }}" disabled>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
