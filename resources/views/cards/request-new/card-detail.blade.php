@extends('banking::cards.request-new.wizard-skeleton')

@section('card-content')
    <div class="px-5 sm:mx-10 md:mx-5 lg:mx-20 mt-10 pt-10 border-t border-gray-200">
        <form action="{{ route('dashboard.cards.store-card-detail') }}" method="POST">
            @csrf

            <input type="hidden" name="workspace_id" value="{{ request()->input('workspace_id') }}">

            <div class="grid grid-cols-12 lg:gap-10 sm:gap-3">
                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-0">
                    <label for="card_holder_name" class="form-label sm:w-30">Card Holder Name <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-4/6">
                        <input id="card_holder_name" name="card_holder_name" type="text"
                               class="form-control @error('card_holder_name') border-theme-6 @enderror"
                               value="{{ old('card_holder_name', $workspace->name) }}">

                        @error('card_holder_name')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-12 md:col-span-8 lg:col-span-6  form-inline mt-2 sm:mt-0">
                    <label for="card_type" class="form-label sm:w-30" style="text-align:right">Card Type <span
                            class="text-theme-6">*</span></label>
                    <div class="sm:w-4/6">
                        <select id="card_type" name="card_type"
                                class="@if ($errors->has('card_type')) border-theme-6 @endif" data-search="true">
                            <option value="debit">Debit</option>
                        </select>

                        @error('card_type')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-right mt-5 form-inline text-right mt-10 float-right">
                <a id="CardDeatilPrevious"
                   href="{{ route('dashboard.cards.show-card-address', ['workspace_id' => session()->get('card_request.workspace_id')]) }}"
                   class="btn btn-secondary w-24 inline-block mr-2">Previous</a>
                <button id="CardDeatilNext" type="submit" class="btn btn-primary w-24">Next</button>
            </div>

        </form>
    </div>
@endsection
