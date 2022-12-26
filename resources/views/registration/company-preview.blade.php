@extends('cms::registration.register-skeleton')

@section('title', 'Personal Account Information')

@section('register-content')

    <div class="col-span-5 h-full">
        <!-- BEGIN: Chat Content -->
        <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
            <div class="tab-content">
                <div id="chats" class="tab-pane active" role="tabpanel" aria-labelledby="chats-tab">
                    <div class="pr-1">
                        <div class="intro-y col-span-12 lg:col-span-12">
                            <!-- BEGIN: Horizontal Form -->
                            <div class="intro-y box mt-0">
                                <div class="flex sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                                    <h2 class="font-medium text-base">Account Confirmation</h2>
                                    <!-- BEGIN: Custom Tooltip Content -->
                                    <span class="lock-amount tooltip ml-auto" data-theme="light"
                                        data-tooltip-content="#custom-content-tooltip" data-trigger="click"
                                        title="This is awesome tooltip example!">
                                        <i data-lucide="info" class="block mx-auto"></i>
                                    </span>
                                    <div class="tooltip-content">
                                        <div id="custom-content-tooltip" class="relative flex items-center py-1">
                                            <div class="ml-4 mr-auto">
                                                <div class="text-gray-600">
                                                    Verify your details and submit.
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- END: Custom Tooltip Content -->
                                </div>
                                <div id="horizontal-form" class="p-5">
                                    <div class="grid grid-cols-12 gap-6 p-3">
                                        @if ($workspace->type == \Kanexy\PartnerFoundation\Membership\Enums\MembershipType::INDIVIDUAL)
                                            <div class="col-span-12 sm:col-span-12 lg:col-span-12 xxl:col-span-12">
                                            @else
                                                <div class="col-span-12 sm:col-span-6 lg:col-span-6 xxl:col-span-6">
                                        @endif
                                        <h4 class="font-bold mb-3">Personal Details</h4>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">First Name</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ $customer->title->name }}
                                                        {{ $customer->first_name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @isset($customer->middle_name)
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Middle Name</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ $customer->middle_name }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Last Name</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ $customer->last_name }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Email</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ $customer->email }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Phone Number</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">+{{ $customer->country_code }}-
                                                        0{{ $customer->phone }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Country of residence</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ $customer->country()->first()->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Nationality</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ $customer->nationality }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Date Of Birth</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $customer->date_of_birth)->format('d-m-Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Bank Account Type</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ ucfirst($workspace->type) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                            <div class="mr-auto">
                                                <div class="text-gray-600 mt-1">Address </div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-full sm:w-64 text-right">
                                                    <div class="font-medium">{{ @$address->house_no }}
                                                        {{ @$address->steet }} {{ @$address->city }}
                                                        {{ @$address->postcode }} {{ @$address->county }}
                                                        {{ @$address->country->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 lg:col-span-6 xxl:col-span-6">
                                        @if ($workspace->type == \Kanexy\PartnerFoundation\Membership\Enums\MembershipType::BUSINESS)
                                            <h4 class="font-bold mb-3">Company Details</h4>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Company Name</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ ucfirst($workspace->name) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($workspace->is_registered == 1)
                                                <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                    <div class="mr-auto">
                                                        <div class="text-gray-600 mt-1">Company Registration Number</div>
                                                    </div>
                                                    <div class="flex">
                                                        <div class="w-full sm:w-64 text-right">
                                                            <div class="font-medium">
                                                                {{ ucfirst($workspace->registration_no) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Company Email</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ ucfirst($workspace->email) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Company Phone</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ ucfirst($workspace->phone) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Company Address</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ @$workspaceAddress->house_no }}
                                                            {{ @$workspaceAddress->steet }}
                                                            {{ @$workspaceAddress->city }}
                                                            {{ @$workspaceAddress->postcode }}
                                                            {{ @$workspaceAddress->county }}
                                                            {{ @$workspaceAddress->country->name }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Officer Name</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ @$currentOfficer->first_name }}
                                                            {{ @$currentOfficer->last_name }} </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Officer Email</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ @$currentOfficer->email }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Officer Phone</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">{{ @$currentOfficer->mobile }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Officer Role</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        <div class="font-medium">
                                                            {{ @$currentOfficer->meta['designation'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @isset($currentOfficer->meta['share_holding_in_percentage'])
                                                <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                    <div class="mr-auto">
                                                        <div class="text-gray-600 mt-1">Officer Share Holding Percentage</div>
                                                    </div>
                                                    <div class="flex">
                                                        <div class="w-full sm:w-64 text-right">
                                                            <div class="font-medium">
                                                                {{ @$currentOfficer->meta['share_holding_in_percentage'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                        @endif
                                        {{-- <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Services | Offer</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        @foreach ($offeredServices as $offeredService)
                                                            <div class="font-medium">{{ $offeredService->name }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex sm:flex-col sm:flex-row  border-b border-gray-200 mb-3">
                                                <div class="mr-auto">
                                                    <div class="text-gray-600 mt-1">Services | Required</div>
                                                </div>
                                                <div class="flex">
                                                    <div class="w-full sm:w-64 text-right">
                                                        @foreach ($requiredServices as $requiredService)
                                                            <div class="font-medium">{{ $requiredService->name }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div> --}}
                                    </div>
                                    <div class="col-span-12 sm:col-span-12 lg:col-span-12 xxl:col-span-12">
                                        <form method="post" action="{{ route('customer.signup.ledger.store') }}"
                                            id="previewForm">
                                            @csrf
                                            <input name="callback_url" id="callback_url" type="hidden">
                                            <input name="document_type" id="document_type" type="hidden">
                                            <div class="intro-y">
                                                <div class="grid grid-cols-12 gap-2 mt-0 pt-0 flex items-end">

                                                    @foreach ($documents as $document)
                                                        @php
                                                            $extension = substr($document->media, strpos($document->media, '.') + 1);
                                                        @endphp

                                                        @if ($DocumentTypeId == $document->document_type_id)
                                                            <div class="col-span-6 sm:col-span-3">
                                                                <video id="video" class="sm:h-48 w-full" controls
                                                                    id="address_proof_img"
                                                                    src="{{ \Illuminate\Support\Facades\Storage::disk('azure')->temporaryUrl($document->media, now()->addMinutes(5)) }}"></video>
                                                            </div>
                                                        @else
                                                            @if ($DocumentSelfieTypeId == $document->document_type_id)
                                                                <div class="col-span-6 sm:col-span-3">
                                                                    @if ($extension == 'application/octet-stream' || $extension == 'application/pdf')
                                                                        <img class="rounded-md proof-default"
                                                                            alt=""
                                                                            src="{{ asset('img/pdf.png') }}">
                                                                    @else
                                                                        <img class="rounded-md proof-default"
                                                                            alt=""
                                                                            src="{{ \Illuminate\Support\Facades\Storage::disk('azure')->temporaryUrl($document->media, now()->addMinutes(5)) }}">
                                                                    @endif
                                                                </div>
                                                            @elseif ($document->holder_type == $workspace->getMorphClass())
                                                                <div class="col-span-6 sm:col-span-3">
                                                                    <img class="w-full sm:h-48"
                                                                        src="{{ \Illuminate\Support\Facades\Storage::disk('azure')->temporaryUrl($document->media, now()->addMinutes(5)) }}">
                                                                    <button type="button" onclick="getChangeDocument(3)"
                                                                        class="btn btn-elevated-primary w-24 mr-1 m-2">
                                                                        Change</button>
                                                                </div>
                                                            @else
                                                                <div class="col-span-6 sm:col-span-3">
                                                                    @if ($extension == 'application/octet-stream')
                                                                        <img class="rounded-md proof-default"
                                                                            alt=""
                                                                            src="{{ asset('img/pdf.png') }}">
                                                                    @else
                                                                        <img class="rounded-md proof-default"
                                                                            alt=""
                                                                            src="{{ \Illuminate\Support\Facades\Storage::disk('azure')->temporaryUrl($document->media, now()->addMinutes(5)) }}">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="flex mt-3">
                                                <div class="w-full px-2">
                                                    <div class="form-inline float-right">
                                                        <button id="CompanyPerview" type="submit"
                                                            class="btn btn-elevated-primary w-24 mr-1">
                                                            Continue
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Chat Content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function getChangeDocument(value) {
            if (value == 1) {
                $('#document_type').val("{{ \Kanexy\Cms\Enums\RegistrationStep::DOCUMENTS }}");
            } else if (value == 2) {
                $('#document_type').val("{{ \Kanexy\Cms\Enums\RegistrationStep::SELFIE_AND_VIDEO }}");
            } else {
                $('#document_type').val("{{ \Kanexy\Cms\Enums\RegistrationStep::COMPANY_DOCUMENTS }}");
            }
            $('#callback_url').val("{{ route('customer.signup.ledger.index') }}");
            $('#previewForm').submit();
        }
    </script>
@endpush
