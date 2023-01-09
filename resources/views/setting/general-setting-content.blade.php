<div id="wrappex-settings" class="tab-pane grid grid-cols-12 gap-3" role="tabpanel" aria-labelledby="wrappex-settings-tab">
    <div class="col-span-12">
        <div class="box">

            <div class="px-5 pb-3">
                <form action="{{ route('dashboard.wrappex-settings.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if (Session::has('error'))
                        <span class="block text-theme-6 pt-5">{{ Session::get('error') }}</span>
                    @endif
                    <div class="grid grid-cols-12 md:gap-3 lg:gap-3 xl:gap-8 mt-0">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="client_id" class="form-label sm:w-52">Wrappex Client Id <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="wrappex_client_id" name="wrappex_client_id" type="text"
                                    class="form-control @error('wrappex_client_id') border-theme-6 @enderror"
                                    placeholder=""
                                    value="{{ old('wrappex_client_id', @$settings['wrappex_client_id']) }}" required>

                                @error('wrappex_client_id')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="wrappex_client_secret" class="form-label sm:w-52">Wrappex Client Secret <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="wrappex_client_secret" name="wrappex_client_secret" type="password"
                                    class="form-control @error('wrappex_client_secret') border-theme-6 @enderror"
                                    placeholder=""
                                    value="{{ old('wrappex_client_secret', @$settings['wrappex_client_secret']) }}"
                                    required>

                                @error('wrappex_client_secret')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 md:gap-3 lg:gap-3 xl:gap-8 mt-0">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="wrappex_email" class="form-label sm:w-52">Wrappex Email <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="wrappex_email" name="wrappex_email" type="text"
                                    class="form-control @error('wrappex_email') border-theme-6 @enderror" placeholder=""
                                    value="{{ old('wrappex_email', @$settings['wrappex_email']) }}" required>

                                @error('wrappex_email')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="wrappex_password" class="form-label sm:w-52">Wrappex Password <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6">
                                <input id="wrappex_password" name="wrappex_password" type="password"
                                    class="form-control @error('wrappex_password') border-theme-6 @enderror"
                                    placeholder="" value="{{ old('wrappex_password', @$settings['wrappex_password']) }}"
                                    required>

                                @error('wrappex_password')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 md:gap-3 lg:gap-3 xl:gap-8 mt-0">
                        <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mt-2">
                            <label for="wrappex_environment" class="form-label sm:w-52">Wrappex Environment <span
                                    class="text-theme-6">*</span></label>
                            <div class="sm:w-5/6 tillselect-marging">
                                <select id="wrappex_environment" name="wrappex_environment" data-search="true"
                                    class="w-full @error('wrappex_environment') border-theme-6 @enderror">
                                    <option value="production" @if ('production' == old('wrappex_environment', @$settings['wrappex_environment'])) selected @endif>
                                        Production</option>
                                    <option value="testing" @if ('testing' == old('wrappex_environment', @$settings['wrappex_environment'])) selected @endif>Testing
                                    </option>
                                </select>

                                @error('wrappex_environment')
                                    <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="text-right mt-5">
                        <button id="GeneralSbumit" type="submit" class="btn btn-primary w-24">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
