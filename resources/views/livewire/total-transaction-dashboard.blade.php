<div class="intro-y box p-5 mt-4 sm:mt-3">
    <div class="flex flex-col xl:flex-row xl:items-center">
        <div class="flex">
            <div>
                <div class="text-lg font-medium mr-auto">Total Transactions</div>
            </div>
        </div>
        <div class="dropdown ml-auto">
            <button id="ChevronDown" class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                <span>{{ $selectedYear }}</span> <span wire:ignore><i data-lucide="chevron-down"
                        class="w-4 h-4 ml-2"></i></span>
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content dark:bg-darkmode-400 dark:border-darkmode-400">
                    <li>
                        @foreach ($years as $year)
                            <a id="SelectYear" wire:click="selectYear({{ $year }})"
                                class="mb-2 dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                {{ $year }}
                            </a>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="flex-col xl:flex-row xl:items-center mt-3">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-6 mt-0">
                <div>
                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">Paid In</div>
                    <div class="text-lg xl:text-xl font-bold">
                        {{ $creditTransaction }}
                        <div class="progress">
                            <div class="progress-bar w-1/2 bg-theme-1" role="progressbar" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 mt-0">
                <div>
                    <div class="mt-0.5 text-gray-600 dark:text-gray-600">Paid Out</div>
                    <div class="text-lg xl:text-xl font-bold">
                        {{ $debitTransaction }}
                        <div class="progress">
                            <div class="progress-bar w-1/2 bg-theme-1" role="progressbar" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
