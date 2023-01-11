@extends('banking::cards.request-new.wizard-skeleton')

@section('card-content')
    <div class="px-5 sm:mx-10 md:mx-5 lg:mx-20 mt-10 pt-10 border-t border-gray-200">
        <form x-data="createRequestNewCardForm()" action="{{ route('dashboard.cards.store') }}" method="POST">
            @csrf

            <input type="hidden" name="workspace_id" value="{{ request()->input('workspace_id') }}">

            <div class="grid grid-cols-12 lg:gap-10 sm:gap-3">
                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="sender_account_id" class="form-label sm:w-30">Bank Account <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6 mob">
                        <select id="account_id" name="account_id"
                                class="@if ($errors->has('account_id')) border-theme-6 @endif" data-search="true"
                                x-ref="activeAccountDropdownRef" x-on:change="changeActiveAccount($el)">
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->bank_code }} /
                                    {{ $account->account_number }}</option>
                            @endforeach
                        </select>

                        @error('account_id')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-2 sm:mt-0">
                    <label for="bank_account_status" class="form-label sm:w-30">Account Status <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-5/6">
                        <input id="bank_account_status" name="bank_account_status" type="text"
                               class="form-control capitalize @error('bank_account_status') border-theme-6 @enderror"
                               x-bind:value="activeAccount?.status" disabled>

                        @error('bank_account_status')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-right mt-5 form-inline text-right mt-5 float-right">
                <a id="CardCancel"
                   href="{{ route('dashboard.cards.index', ['filter' => ['workspace_id' => $workspace->id]]) }}"
                   class="btn btn-secondary w-24 inline-block mr-2">Cancel</a>
                <button id="CardSubmit" type="submit" class="btn btn-primary w-24">Next</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function createRequestNewCardForm() {
            return {
                accounts: {!! $accounts->toJson() !!},
                activeAccountId: '',

                get activeAccount() {
                    return this.accounts.find(account => account.id == this.activeAccountId);
                },

                init() {
                    this.changeActiveAccount(this.$refs.activeAccountDropdownRef);
                },

                changeActiveAccount($el) {
                    this.activeAccountId = $el.value;
                },
            };
        }
    </script>
@endpush
