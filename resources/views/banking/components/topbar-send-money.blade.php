@if (auth()->user()->isSubscriber())
<div class="col-span-6 sm:col-span-3 lg:col-span-2 xl:col-span-1 flex items-center">
    <div class="relative">
        <div class="sm:p-0 flex gap-2 sm:gap-3">
            <a id="Sender" @if (!is_null(app('activeWorkspaceId'))) href="{{ route('dashboard.banking.payouts.index', ['workspace_id' => app('activeWorkspaceId')]) }}" @endif
                class="text-theme-6 flex items-center block p-0 transition duration-300 ease-in-out rounded-md tooltip"
                data-theme="light" title="Send">
                <i data-lucide="send" class="w-6 h-6 mr-0"></i>
            </a>
            <a id="Receiver" class="text-success flex items-center block p-0 transition duration-300 ease-in-out rounded-md tooltip"
                data-theme="light" title="Receive">
                <i data-lucide="pocket" class="w-6 h-6 mr-0"></i>
            </a>
        </div>
    </div>
</div>
@endif

