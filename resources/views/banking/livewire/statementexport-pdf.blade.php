<div class="p-0">
    <div class="grid grid-cols-12 md:gap-3 mt-0">
        <div class="col-span-12 md:col-span-12 form-inline">
            <div class="sm:w-5/6 tillselect-marging">
                <form>
                    <input type="hidden" wire:model="workspace_id" />
                    <div class="col-span-12 md:col-span-6 form-inline mt-2">
                        <label for="postcode" class="form-label sm:w-20">Month <span class="text-theme-6">*</span></label>
                        <div class="sm:w-3/5 mr-3">
                            <input type="hidden" name="duration">
                            <select class="form-control w-full month-export"
                                    wire:change="changeMonth($event.target.value)" data-search="true" multiple>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                            @if (in_array($i, $month)) selected @endif>
                                        {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-right mt-0">
                            <button id="StatementMonthExport" type="button" wire:click="statementMonthExport()"
                                    class="btn btn-primary w-24">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 md:gap-3 mt-3">
        <div class="col-span-12 md:col-span-12 form-inline">
            <div class="sm:w-5/6 tillselect-marging">
                <form>
                    <input type="hidden" wire:modal="workspace_id" />
                    <div class="col-span-12 md:col-span-6 form-inline mt-2">
                        <label for="postcode" class="form-label sm:w-20">Year <span
                                class="text-theme-6">*</span></label>
                        <div class="sm:w-3/5 mr-3">
                            <input type="hidden" name="duration">
                            <select wire:change="changeYear($event.target.value)" data-search="true"
                                    class="tail-select w-full year-export">
                                <option>Select</option>
                                @for ($i = 2011; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}"
                                            @if ($selectedYear == $i) selected @endif>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                            <span class="block text-theme-6 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-right mt-0">
                            <button id="StatementYearExport" type="button" wire:click="statementYearExport()"
                                    class="btn btn-primary w-24">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
