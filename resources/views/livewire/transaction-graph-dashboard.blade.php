<div class="box shadow-lg box p-2">
    <div class="hidden" id="updateCredit">{{ $creditTransactionGraphData }}</div>
    <div class="hidden" id="updateDebit">{{ $debitTransactionGraphData }}</div>
    <div class="flex flex-col xl:flex-row xl:items-center">
        <div class="flex">
            <div>
                <div class="text-lg font-medium mr-auto mt-2">Transaction</div>
            </div>
        </div>
        <div class="dropdown ml-auto">
            <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                <span>{{ $selectedYear }}</span> <span wire:ignore><i data-lucide="chevron-down"
                        class="w-4 h-4 ml-2"></i></span>
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">
                    <li>
                        @foreach ($years as $year)
                            <a wire:click="selectYear({{ $year }})"
                                class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                {{ $year }}
                            </a>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <canvas id="chartLine" class="h-full w-full"></canvas>
</div>
