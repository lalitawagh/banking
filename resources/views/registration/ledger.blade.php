@extends('cms::registration.register-skeleton')

@section('title', 'Account Preview')

@section('register-content')

    <div class="col-span-5 h-full">
        <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
            <div class="tab-content">
                <div class="grid grid-cols-12 gap-6 mt-0">
                    <div class="col-span-12 lg:col-span-12">
                        <div class="flex sm:flex-row items-center p-3 border-b border-gray-200 dark:border-dark-5">
                            <h2 class="font-medium text-base">Account Preview</h2>
                            <span class="lock-amount tooltip ml-auto" data-theme="light"
                                data-tooltip-content="#custom-content-tooltip" data-trigger="click"
                                title="This is awesome tooltip example!">
                                <i data-lucide="info" class="block mx-auto"></i>
                            </span>
                            <div class="tooltip-content">
                                <div id="custom-content-tooltip" class="relative flex items-center py-1">
                                    <div class="ml-4 mr-auto">
                                        <div class="text-gray-600">
                                            Please stay with us until account is being activated
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-col xl:flex-row xl:items-center mt-3">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 lg:col-span-6 mt-8">
                                    <div class="flex">
                                        <div class="w-full mb-2 px-4 sm\:w-auto">
                                            <h2 class="text-base mr-auto">Current Balance : <span class="font-medium">
                                                    {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($ledger?->balance) }}
                                                </span></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-6 mt-8">
                                    <div class="flex">
                                        <div class="w-full mb-2 px-4 sm\:w-auto sm:text-right">
                                            <h2 class="text-base ">Account Status: <span class="font-medium">
                                                    @if ($workspace?->status === 'active')
                                                        Active
                                                    @else
                                                        Inactive
                                                    @endif
                                                </span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 ml-4">
                                <div class="font-medium">Thank you for choosing {{ config('app.name') }}. Your account has been created successfully. Please wait till we process your information.</div>

                                <div class="font-medium"> Kindly, perform transactions of sending and receiving money once your account is activated.  It may take up to 2-3 business days.</div>
                            </div>

                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 lg:col-span-6 mt-8">
                                    <div class="flex">
                                        <div class="w-full mb-2 px-4 sm\:w-auto">
                                            <div class="form-check mr-2">
                                                <h2 class="font-medium text-base mr-auto">Account Details</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3 mx-3">
                                <div
                                    class="bg-gray-300 intro-y col-span-12 md:col-span-6 lg:col-span-6 text-theme-1 dark:text-theme-10">
                                    <div class="bg-gray-300">
                                        <div class="text-lg font-medium text-theme-1 dark:text-theme-10 px-5 pt-5">
                                            For Domestic Transfer</div>
                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">Beneficiary</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $workspace?->name }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">Account Number</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $ledger?->account_number }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">Sort Code</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $ledger?->bank_code }}
                                                </div>
                                            </div>
                                        </div>




                                        <div class="flex items-center">
                                            <div class="text-xs mx-auto text-center sm:ml-auto flex mb-3">
                                                <a target="_blank"
                                                    href="https://mail.google.com/mail/u/0/?fs=1&tf=cm&subject=Account Detail&body=For Domestic Transfer %0D%0A Beneficiary :- {{ $workspace?->name }} %0D%0A Account Number :- {{ $ledger?->account_number }} %0D%0A Sort Code :- {{ $ledger?->bank_code }}  ">
                                                    <i data-lucide="share-2" class="block mx-auto mr-2"></i>
                                                </a>
                                                <a onclick="get_pdf('domestic')" href="javascript:void(0);"><i
                                                        data-lucide="download" class="block mx-auto mr-2"> </i></a>
                                                <a onclick="copyData(this)"
                                                    data-copy="For Domestic Transfer - Beneficiary :- {{ $workspace?->name }} Account Number :- {{ $ledger?->account_number }} Sort Code :- {{ $ledger?->bank_code }} "
                                                    href="javascript:void(0);">
                                                    <i data-lucide="copy" class="block mx-auto mr-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-300 intro-y col-span-12 md:col-span-6 lg:col-span-6 text-theme-1 dark:text-theme-10">
                                    <div class="bg-gray-300">
                                        <div class="text-lg font-medium text-theme-1 dark:text-theme-10 px-5 pt-5">
                                            For International Transfer</div>

                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">Beneficiary</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $workspace?->name }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">IBAN</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $ledger?->iban_number }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col lg:flex-row px-5 py-1">
                                            <div class="w-48">
                                                <div class="mt-1">BIC/SWIFT Code</div>
                                            </div>
                                            <div class="mt-1 lg:mt-0 w-48">
                                                <div class="font-medium text-gray-600 mt-1">{{ $ledger?->bic_swift }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="flex flex-col lg:flex-row px-5 pt-5  pb-5">
                                        <div class="w-48">

                                            <div class="mt-1">Beneficiary</div>
                                            <div class="mt-1">IBAN</div>
                                            <div class="mt-1">BIC/SWIFT Code</div>
                                        </div>
                                        <div class="mt-10 lg:mt-0">

                                            <div class="font-medium text-gray-600 mt-1">{{ $workspace?->name }}</div>
                                            <div class="font-medium text-gray-600 mt-1">{{ $ledger?->iban_number }}</div>
                                            <div class="font-medium text-gray-600 mt-1">{{ $ledger?->bic_swift }}</div>
                                         </div>
                                    </div> --}}
                                        <div class="flex items-center">
                                            <div class="text-xs mx-auto text-center sm:ml-auto flex mb-3">
                                                <a target="_blank"
                                                    href="https://mail.google.com/mail/u/0/?fs=1&tf=cm&subject=Account Detail&body=For International Transfer  %0D%0A Beneficiary :- {{ $workspace?->name }} %0D%0A IBAN :- {{ $ledger?->iban_number }} %0D%0A BIC/SWIFT Code :- {{ $ledger?->bic_swift }}">
                                                    <i data-lucide="share-2" class="block mx-auto mr-2"></i>
                                                </a>
                                                <a onclick="get_pdf('international')" href="javascript:void(0);"><i
                                                        data-lucide="download" class="block mx-auto mr-2"> </i></a>
                                                <a onclick="copyData(this)"
                                                    data-copy="For International Transfer - Beneficiary :- {{ $workspace?->name }} IBAN :- {{ $ledger?->iban_number }}  BIC/SWIFT Code :- {{ $ledger?->bic_swift }} "
                                                    href="javascript:void(0);">
                                                    <i data-lucide="copy" class="block mx-auto mr-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 mt-8">
                                <div class="flex my-3">
                                    <div class="w-full px-2">
                                        <div class="form-inline float-right">
                                            <a href="{{ route('dashboard.index') }}"
                                                class="btn btn-elevated-primary w-auto mr-1">
                                                Explore Dashboard
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @includeWhen($ledger?->account_number == '', 'partner-foundation::core.registration-account-alert')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>

    <script>
        function get_pdf(type) {
            var doc = new jsPDF();
            var specialElementHandlers = {
                '#editor': function(element, renderer) {
                    return true;
                }
            };
            if (type == 'domestic') {
                doc.fromHTML(
                    '<h2>Account Details</h2><div><div class="mt-1"><h3>For Domestic Transfer </h3></br></div><div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2"> Beneficiary :- {{ $workspace?->name }} </br></div><div class="mt-1">Account Number :- {{ $ledger?->account_number }} </br></div><div class="mt-1">Sort Code :- {{ $ledger?->bank_code }} </br></div></div>',
                    15, 15, {
                        'width': 170,
                        'elementHandlers': specialElementHandlers
                    });
                doc.save('preview-ledger-domestic.pdf');
            } else {
                doc.fromHTML(
                    '<h2>Account Details</h2><div><div class="mt-1"><h3>For International Transfer </h3></br></div><div class="text-lg font-medium text-theme-1 dark:text-theme-10 mt-2"> Beneficiary :- {{ $workspace?->name }} </br></div><div class="mt-1">IBAN :- {{ $ledger?->iban_number }} </br></div><div class="mt-1">BIC/SWIFT Code :- {{ $ledger?->bic_swift }} </br></div></div>',
                    15, 15, {
                        'width': 170,
                        'elementHandlers': specialElementHandlers
                    });
                doc.save('preview-ledger-international.pdf');
            }

        }

        function copyToClipboard(text, el) {
            var copyTest = document.queryCommandSupported('copy');
            var elOriginalText = el.attr('data-original-title');

            if (copyTest === true) {
                var copyTextArea = document.createElement("textarea");
                copyTextArea.value = text;
                document.body.appendChild(copyTextArea);
                copyTextArea.select();
                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'Copied!' : 'Whoops, not copied!';
                    el.attr('data-original-title', msg).tooltip('show');
                } catch (err) {
                    console.log('Oops, unable to copy');
                }
                document.body.removeChild(copyTextArea);
                el.attr('data-original-title', elOriginalText);
            } else {
                // Fallback if browser doesn't support .execCommand('copy')
                window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
            }
        }

        function copyData(the) {
            var text = $(the).attr('data-copy');
            var el = $(the);
            copyToClipboard(text, el);
        }
    </script>
@endpush
