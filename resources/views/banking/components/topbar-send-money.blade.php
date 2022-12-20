{{-- <div data-url="{{ route('dark-mode-switcher') }}"
    class="dark-mode-switcher cursor-pointer shadow-md bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 ml-2">
    <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
    <div class="dark-mode-switcher__toggle {{ $dark_mode ? 'dark-mode-switcher__toggle--active' : '' }} border"></div>
</div> --}}
@if (config('services.disable_banking') == false)
    <div class="col-span-6 sm:col-span-3 lg:col-span-2 xl:col-span-1 flex items-center">
        <div class="relative">
            <div class="sm:p-0 flex gap-2 sm:gap-3">
                <a @if (!is_null($activeWorkspaceId)) href="{{ route('dashboard.banking.payouts.index', ['workspace_id' => $activeWorkspaceId]) }}" @endif
                    class="text-theme-6 flex items-center block p-0 transition duration-300 ease-in-out rounded-md tooltip"
                    data-theme="light" title="Send">
                    <i data-lucide="send" class="w-6 h-6 mr-0"></i>
                </a>
                <a class="text-success flex items-center block p-0 transition duration-300 ease-in-out rounded-md tooltip"
                    data-theme="light" title="Receive">
                    <i data-lucide="pocket" class="w-6 h-6 mr-0"></i>
                </a>
            </div>
        </div>
    </div>
@endif
