@extends('cms::dashboard.layouts.default')

@section('title', 'Create Beneficiary')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center py-2 px-3 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Create Beneficiary
                    </h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('dashboard.banking.beneficiaries.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input name="workspace_id" type="hidden" value="{{ $workspace->id }}">
                        <input name="classification[]" type="hidden" value="beneficiary">
                        <input name="meta[bank_code_type]" type="hidden" value="sort-code">
                        <input name="callback_url" type="hidden" value="{{ request()->query('callback_url') }}">

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label class="form-label sm:w-30">Contact Type <span class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6 sm:pt-1">
                                    <div class="flex sm:flex-row mt-2">
                                        <div class="form-check mr-6">
                                            <input id="type-personal" class="form-check-input contact-type" type="radio"
                                                name="type" value="personal" checked>
                                            <label class="form-check-label" for="type-personal">Personal</label>
                                        </div>

                                        <div class="form-check mr-2 sm:mt-0">
                                            <input id="type-company" class="form-check-input contact-type" type="radio"
                                                name="type" value="company"
                                                @if (old('type') == 'company') checked @endif>
                                            <label class="form-check-label" for="type-company">Company</label>
                                        </div>
                                    </div>

                                    @error('type')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0 contact-personal">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="first_name" class="form-label sm:w-30">First Name <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="first_name" name="first_name" type="text"
                                        class="form-control @error('first_name') border-theme-6 @enderror"
                                        value="{{ old('first_name') }}">

                                    @error('first_name')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="middle_name" class="form-label sm:w-30">Middle Name</label>
                                <div class="sm:w-5/6">
                                    <input id="middle_name" name="middle_name" type="text"
                                        class="form-control @error('middle_name') border-theme-6 @enderror"
                                        value="{{ old('middle_name') }}">

                                    @error('middle_name')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0 contact-personal">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="last_name" class="form-label sm:w-30">Last Name <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="last_name" name="last_name" type="text"
                                        class="form-control @error('last_name') border-theme-6 @enderror"
                                        value="{{ old('last_name') }}">

                                    @error('last_name')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">

                                <label for="" class="form-label sm:w-30">Email Address</label>
                                <div class="sm:w-5/6">
                                    <input id="email" name="email" type="email"
                                        class="form-control @error('') border-theme-6 @enderror"
                                        value="{{ old('email') }}">


                                </div>
                            </div>

                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0 contact-company hidden">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="company_name" class="form-label sm:w-30">Company Name <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="company_name" name="company_name" type="text"
                                        class="form-control @error('company_name') border-theme-6 @enderror"
                                        value="{{ old('company_name') }}">

                                    @error('company_name')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="email" class="form-label sm:w-30">Email Address</label>
                                <div class="sm:w-5/6">
                                    <input id="email" name="email" type="email"
                                        class="form-control @error('email') border-theme-6 @enderror"
                                        value="{{ old('email') }}">


                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="landline" class="form-label sm:w-30">Landline No.</label>
                                <div class="sm:w-5/6">
                                    <input id="landline" name="landline" type="text"
                                        class="form-control @error('landline') border-theme-6 @enderror"
                                        value="{{ old('landline') }}">

                                    @error('landline')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="mobile" class="form-label sm:w-30">Mobile No.</label>
                                <div class="sm:w-5/6">
                                    <input id="mobile" name="mobile" type="text"
                                        class="form-control @error('mobile') border-theme-6 @enderror"
                                        value="{{ old('mobile') }}" onKeyPress="if(this.value.length==11) return false;">

                                    @error('mobile')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="bank_account_name" class="form-label sm:w-30"> Account Name <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="bank_account_name" name="meta[bank_account_name]" type="text"
                                        class="form-control @error('meta.bank_account_name') border-theme-6 @enderror"
                                        value="{{ old('meta.bank_account_name') }}" required>

                                    @error('meta.bank_account_name')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="bank_account_number" class="form-label sm:w-30"> Account No. <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="bank_account_number" name="meta[bank_account_number]" type="text"
                                        class="form-control @error('meta.bank_account_number') border-theme-6 @enderror"
                                        value="{{ old('meta.bank_account_number') }}" required>

                                    @error('meta.bank_account_number')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="bank_code" class="form-label sm:w-30"> Sort Code <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6">
                                    <input id="bank_code" name="meta[bank_code]" type="text"
                                        class="form-control @error('meta.bank_code') border-theme-6 @enderror"
                                        value="{{ old('meta.bank_code') }}" required>

                                    @error('meta.bank_code')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="avatar" class="form-label sm:w-30">Avatar</label>
                                <div class="sm:w-5/6">
                                    <input id="avatar" name="avatar" type="file"
                                        class="form-control @error('avatar') border-theme-6 @enderror"
                                        value="{{ old('avatar') }}">

                                    @error('avatar')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 md:gap-0 lg:gap-3 xl:gap-10 mt-0">
                            <div class="col-span-12 md:col-span-12 lg:col-span-6 form-inline mb-2">
                                <label for="bank_country" class="form-label sm:w-30"> Country <span
                                        class="text-theme-6">*</span></label>
                                <div class="sm:w-5/6 tillselect-marging">
                                    <select id="bank_country" name="meta[bank_country]" data-search="true"
                                        class="w-full @error('meta.bank_country') border-theme-6 @enderror">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->getKey() }}"
                                                @if ($country->getKey() == old('meta.bank_country', $defaultCountry)) selected @endif>{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('meta.bank_country')
                                        <span class="block text-theme-6 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-5">
                            <a id="BeneficiaryCancel"
                                href="{{ route('dashboard.banking.beneficiaries.index', ['filter' => ['workspace_id' => $workspace->id]]) }}"
                                class="btn btn-secondary w-24 inline-block mr-1">Cancel</a>
                            <button id="BeneficiaryCreate" type="submit" class="btn btn-primary w-24">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function contactTypeChange(val) {
            if (val == "{{ \Kanexy\PartnerFoundation\Banking\Enums\ContactType::COMPANY }}") {
                $(".contact-company").removeClass('hidden hiddenform');
                $(".contact-company").addClass('visible');
                $(".contact-company #company_name").attr('required', 'required');
                $("#first_name, #middle_name, #last_name").val('');

                $(".contact-personal").removeClass('visible');
                $(".contact-personal").addClass('hidden hiddenform');
                $(".contact-personal #first_name, #last_name").removeAttr('required');
            } else {
                $(".contact-company").removeClass('visible');
                $(".contact-company").addClass('hidden hiddenform');
                $(".contact-company #company_name").removeAttr('required');



                $(".contact-personal").removeClass('hidden hiddenform');
                $(".contact-personal").addClass('visible');
                $(".contact-personal #first_name, #last_name").attr('required', 'required');
                $(".contact-personal #middle_name").removeAttr('required');
                $(".contact-personal #email").removeAttr('required');
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
    </script>
@endpush

