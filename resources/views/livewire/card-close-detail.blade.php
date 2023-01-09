<div>
    <form action="@isset($cardId){{ route('dashboard.cards.close', $cardId) }}@endisset"
        method="POST">
        @csrf
        <div class="grid grid-cols-12 gap-4 mt-3">
            <div class="col-span-12 sm:col-span-12 form-inline">
                <label for="close-reason" class="form-label sm:w-28">Name </label>
                <div class="sm:w-4/6">
                    <input type="text" name="card_holder_name" id="card_holder_name" wire:model.defer="card_holder_name"
                        disabled class="form-control @error('card_holder_name') border-theme-6 @enderror">
                </div>
            </div>
            <div class="col-span-12 sm:col-span-12 form-inline">
                <label for="close-reason" class="form-label sm:w-28">Close Reason <span
                        class="text-theme-6">*</span></label>
                <div class="sm:w-4/6" wire:ignore>
                    <select name="close_reason" id="close_reason" class="form-control w-full" data-search="true">
                        <option value="{{ \Kanexy\Banking\Enums\CardEnum::CARD_STOLEN }}">
                            {{ ucwords(str_replace('-', ' ', \Kanexy\Banking\Enums\CardEnum::CARD_STOLEN)) }}
                        </option>
                        <option value="{{ \Kanexy\Banking\Enums\CardEnum::CARD_DESTROYED }}">
                            {{ ucwords(str_replace('-', ' ', \Kanexy\Banking\Enums\CardEnum::CARD_DESTROYED)) }}
                        </option>
                    </select>

                    @error('close_reason')
                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-right w-full mb-0 mt-5">
            <div class="form-inline">
                <label class="form-label sm:w-28"></label>
                <div class="sm:w-4/6 pb-20">
                    <a id="cardCloseCancel" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</a>
                    <button id="cardCloseSubmit" type="submit" class="btn btn-primary w-20 mr-2">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
