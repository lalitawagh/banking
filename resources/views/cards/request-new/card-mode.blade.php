@extends('partner-foundation::cards.request-new.wizard-skeleton')

@section('card-content')
    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
        <form action="{{ route('dashboard.cards.store-card-mode') }}" method="POST">
            @csrf

            <input type="hidden" name="workspace_id" value="{{ request()->input('workspace_id') }}">

            <div class="box w-full form-check p-3">
                <input id="card-virtual" class="form-check-input" type="radio" name="mode" value="virtual" checked>

                <label class="form-check-label flex-grow ml-5" for="card-virtual">
                    <div class="flex flex-col lg:flex-row justify-between items-center">
                        <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                            <span class="font-medium text-lg">Virtual</span>
                            <div class="text-gray-600 mt-0.5">Best for online purchases and subscriptions.</div>
                        </div>

                        <div class="w-40">
                            <img src="{{ asset('dist/images/card1.png') }}">
                        </div>
                    </div>
                </label>
            </div>

            @error('mode')
            <span class="block text-theme-6 mt-2">{{ $message }}</span>
            @enderror

            <div class="text-right mt-5 form-inline text-right mt-5 float-right">
                <a id="CardPrevious"
                   href="{{ route('dashboard.cards.create', ['workspace_id' => session()->get('card_request.workspace_id')]) }}"
                   class="btn btn-secondary w-24 inline-block mr-2">Previous</a>
                <button id="CardSubmit" type="submit" class="btn btn-primary w-24">Next</button>
            </div>
        </form>
    </div>
@endsection
