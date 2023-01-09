{{-- <a id="PaymentSettings-tab"  href="javascript:;"  data-tw-toggle="tab" data-tw-target="#payment-settings" role="tab" aria-controls="PaymentSettings" aria-selected="false" class="flex items-center px-3 py-2 mt-2">
    Payment Settings
</a> --}}
@if(auth()->user()->hasPermissionTo(\Kanexy\PartnerFoundation\Core\Enums\Permission::WRAPPEX_SETTINGS_VIEW))
    <li class="nav-item"><a id="WrappexSettings-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#wrappex-settings"
        role="tab" aria-controls="WrappexSettings" aria-selected="false"
        class="nav-link flex items-center px-3 py-2 mt-2">
        Wrappex Settings
    </a></li>
@endif

