@extends('cms::dashboard.layouts.default')

@section('title', 'Cards')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Cards
                    </h2>
                </div>

                <div class="px-5 py-3">
                    <div class="intro-y mt-0">
                        <div
                            class="sm:flex justify-end flex-wrap items-center sm:py-1 border-b border-gray-200 dark:border-dark-5 gap-1">
                            <x-list-view-filters />
                            @can(\Kanexy\PartnerFoundation\Banking\Policies\CardPolicy::CREATE,
                                \Kanexy\PartnerFoundation\Banking\Models\Card::class)
                                <div>
                                    <a href="{{ route('dashboard.cards.create', ['workspace_id' => $workspace->id]) }}"
                                        class="btn btn-sm btn-primary shadow-md sm:ml-1 sm:-mt-2 sm:mb-0 mb-2 py-2">Request
                                        New Card</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="overflow-x-auto overflow-y-hidden">
                        <table class="shroting display table table-report -mt-2">
                            <thead class="short-wrp dark:bg-darkmode-400 dark:border-darkmode-400">
                                <tr>
                                    <th class="whitespace-nowrap text-left">#</th>
                                    <th class="whitespace-nowrap text-left">Name
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-left">Bank Account
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-left">Mode
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-left">Type
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-left">Expiry Date
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-left">Status
                                        <span class="flex short-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 up" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 down" fill="#c1c4c9"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="whitespace-nowrap text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cards as $index => $card)
                                    <tr @if ($index % 2 === 0) class="" @endif>
                                        <td class="whitespace-nowrap text-left">{{ $index + 1 }}</td>
                                        <td class="whitespace-nowrap text-left">{{ $card->name }}</td>
                                        <td class="whitespace-nowrap text-left">{{ $card->account->bank_code }} /
                                            {{ $card->account->account_number }}</td>
                                        <td class="whitespace-nowrap text-left capitalize">{{ $card->mode }}</td>
                                        <td class="whitespace-nowrap text-left capitalize">{{ $card->type }}</td>
                                        <td class="whitespace-nowrap text-left">{{ $card->expiry_date }}</td>
                                        <td class="whitespace-nowrap text-left capitalize">{{ $card->status }}</td>
                                        <td class="whitespace-nowrap text-center">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle btn px-2 box" aria-expanded="false"
                                                    data-tw-toggle="dropdown">
                                                    <span class="w-5 h-5 flex items-center justify-center">
                                                        <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                                                    </span>
                                                </button>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="{{ route('dashboard.cards.show', $card->id) }}"
                                                                class="dropdown-item flex items-center block dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white">
                                                                <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Show
                                                            </a>
                                                        </li>

                                                        <li>
                                                            @can(\Kanexy\PartnerFoundation\Banking\Policies\CardPolicy::APPROVE,
                                                                $card)
                                                                <form
                                                                    action="{{ route('dashboard.cards.approve', $card->id) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <button type="submit"
                                                                        class="w-full flex items-center block dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white">
                                                                        <i data-lucide="toggle-right"
                                                                            class="w-4 h-4 mr-2"></i> Approve
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </li>
                                                        <li>
                                                            @can(\Kanexy\PartnerFoundation\Banking\Policies\CardPolicy::ACTIVATE,
                                                                $card)
                                                                <form
                                                                    action="{{ route('dashboard.cards.activate', $card->id) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <button type="submit"
                                                                        class="w-full flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-green-200 dark:hover:bg-dark-2 rounded-md">
                                                                        <i data-lucide="toggle-right"
                                                                            class="w-4 h-4 mr-2"></i> Activate
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </li>
                                                        <li>
                                                            @can(\Kanexy\PartnerFoundation\Banking\Policies\CardPolicy::CLOSE,
                                                                $card)
                                                                <a href="javascript:void(0);"
                                                                    onclick="Livewire.emit('cardCloseId', {{ $card->id }})"
                                                                    data-tw-toggle="modal" data-tw-target="#card-close-modal"
                                                                    class="dropdown-item flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Card Close
                                                                </a>
                                                            @endcan
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>

                    <div id="card-close-modal" class="modal modal-slide-over" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header p-5">
                                    <h2 class="font-medium text-base mr-auto">Card Close Details</h2>
                                </div>
                                <div class="modal-body">
                                    <livewire:card-close-detail />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2">
                        {{ $cards->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
