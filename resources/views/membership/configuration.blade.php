@extends('partner-foundation::membership.membership-layout.membership-skeleton')

@section('title', 'Membership Configuration')

@section('membership-content')
    <div id="Configuration"
        class="tab-pane grid grid-cols-12 gap-3 {{ request()->routeIs('dashboard.membership-configuration-information', [request()->route('membershipId'), request()->route('workspaceId')]) ? 'active' : '' }}"
        role="tabpanel" aria-labelledby="BankInformation-tab">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y box mt-0">
                <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Configuration
                    </h2>
                </div>

                <div class="preview p-5">
                    @if ($account?->id)
                        <form action="{{ route('dashboard.membership.store.configuration', $account?->id) }}" method="post">
                            @csrf
                            @php
                                $setting = $account->meta()->pluck('value', 'key');
                            @endphp
                            <input type="hidden" name="ledger_id" value="{{ $account?->id }}">

                            <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-3 mt-0">
                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">One time withdrawal limit </label>
                                    <input type="number" class="form-control" name="setting[one_time_withdrawal_limit]"
                                        value="{{ @$setting['one_time_withdrawal_limit'] }}">
                                </div>

                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">Max day withdrawal limit</label>
                                    <input type="number" class="form-control" name="setting[max_day_withdrawal_limit]"
                                        value="{{ @$setting['max_day_withdrawal_limit'] }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-3 mt-0">
                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">Max month withdrawal limit</label>
                                    <input type="number" class="form-control" name="setting[max_month_withdrawal_limit]"
                                        value="{{ @$setting['max_month_withdrawal_limit'] }}">
                                </div>
                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">Max day transaction limit </label>
                                    <input type="number" class="form-control" name="setting[max_day_txn_limit]"
                                        value="{{ @$setting['max_day_txn_limit'] }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-3 mt-0">
                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">Max month transaction limit</label>
                                    <input type="number" class="form-control" name="setting[max_month_txn_limit]"
                                        value="{{ @$setting['max_month_txn_limit'] }}">
                                </div>
                                <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                                    <label class="form-label sm:w-64">Max per transaction limit <span
                                            class="text-theme-6">*</span></label>
                                    <input type="number" class="form-control" name="setting[max_per_txn_limit]"
                                        value="{{ @$setting['max_per_txn_limit'] }}" required>
                                </div>
                            </div>

                            <div class="text-right mt-3">
                                <button id="configurationSave" type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
