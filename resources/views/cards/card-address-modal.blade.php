<div id="card-{{ $addressType }}-address-modal" class="modal modal-slide-over" data-backdrop="static" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg ihphone-scroll-height">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">New {{ \Illuminate\Support\Str::title($addressType) }} Address
                </h2>
            </div>

            <livewire:workspace-address :workspace="$workspace" :addressType="$addressType" />
        </div>
    </div>
</div>
<style>
    .tail-select {
        margin-top: 0.5rem !important;
    }
</style>
@push('scripts')
    <script src="https://cdn.getaddress.io/scripts/jquery.getAddress-4.0.0.min.js"></script>
    <script>
        $('#{{ $addressType }}_postcode_lookup').getAddress({
            api_key: "{{ config('services.getaddress.api_key') }}",
            output_fields: {
                line_1: '#line1_1',
                line_2: '#line2_1',
                line_3: '#line3_1',
                post_town: '#town_1',
                county: '#county_1',
                postcode: '#postcode_1'
            },
            dropdown_class: "form-control col-span-12 mt-2",
            button_class: "col-span-2 sm:col-span-2 btn btn-primary ml-2 searchButton",
            input_class: "col-span-10 sm:col-span-10 form-control mt-0",
            button_disabled_message: '<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="postcode-serch feather feather-search search__icon dark:text-gray-300 search__icon dark:text-gray-300"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            input_label: "Enter your Postcode",
            button_label: '<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="postcode-serch feather feather-search search__icon dark:text-gray-300 search__icon dark:text-gray-300"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            onLookupSuccess: function(elem, index) {
                if ($('#{{ $addressType }}_postcode_lookup').find("#getaddress_input").val() ==
                    'Enter your Postcode') {
                    $('#{{ $addressType }}_postcode_lookup').find("#getaddress_button").html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="postcode-serch feather feather-search search__icon dark:text-gray-300 search__icon dark:text-gray-300"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'
                        );
                    $('#{{ $addressType }}_postcode_lookup').find("#getaddress_button").addClass(
                        "col-span-2 sm:col-span-2 btn btn-primary mr-0");
                    $('#{{ $addressType }}_postcode_lookup').find("#getaddress_dropdown").hide();
                    $('#{{ $addressType }}_postcode_lookup').find("#getaddress_input").focus();
                }
                tailSelect();
                $(".tail-select").addClass('col-span-12');
            },
            onAddressSelected: function(elem, index) {
                $.get('https://api.getaddress.io/get/' + $('#getaddress_dropdown').val(), {
                    'api-key': "{{ config('services.getaddress.api_key') }}"
                }, function(address, status) {
                    Livewire.emit("postAddress", address.building_number, address.thoroughfare, address
                        .sub_building_name, address.town_or_city, address.district, address
                        .postcode, address.country, '{{ $addressType }}');
                });
            }
        });
        $('#{{ $addressType }}_postcode_lookup').find("#getaddress_button").each(function(e) {
            $(this).click(function() {
                $(this).parent().children(".tail-select").remove();
                if ($(this).prev("#getaddress_input").val() == 'Enter your Postcode') {
                    $(this).html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="postcode-serch feather feather-search search__icon dark:text-gray-300 search__icon dark:text-gray-300"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'
                        );
                    $(this).addClass("col-span-2 sm:col-span-2 btn btn-primary mr-0");
                    $(this).next("#getaddress_dropdown").hide();
                }
            });
        });
    </script>
@endpush
