<li id="BankInformation-tab" class="nav-item flex-1" role="presentation">
    <a data-tw-toggle="pill" data-tw-target="#BankInformation-tab" type="button" role="tab"
        aria-controls="BankInformation-tab" aria-selected="true"
        href="{{ route('dashboard.membership-bank-information', app('activeWorkspaceId')) }}"
        class="nav-link w-full px-3 py-2 mt-2 {{ request()->routeIs('dashboard.membership-bank-information', app('activeWorkspaceId')) ? 'active' : '' }}">
        Bank Information
    </a>
</li>
@if (!Auth::user()->isSubscriber())
<li id="Configuration-tab" class="nav-item flex-1" role="presentation">
    <a data-tw-toggle="pill" data-tw-target="#Configuration-tab" type="button" role="tab"
        aria-controls="Configuration-tab" aria-selected="true"
        href="{{ route('dashboard.membership-configuration-information', app('activeWorkspaceId')) }}"
        class="nav-link w-full px-3 py-2 mt-2 {{ request()->routeIs('dashboard.membership-configuration-information', app('activeWorkspaceId')) ? 'active' : '' }}">
        Configuration
    </a>
</li>
@endif
