@extends('cms::dashboard.layouts.default')

@section('title', 'Request New Card')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box relative">
                <div class="flex items-center p-3 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Request New Card
                    </h2>
                </div>

                <div class="intro-y py-10 sm:py-10 mt-3 clearfix">
                    <div
                        class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex lg:flex-row justify-center px-5 sm:px-20 wizard flex flex-col lg:flex-row justify-center px-10">
                        <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                            <button
                                class="w-10 h-10 rounded-full btn text-slate-500 bg-slate-100 dark:bg-darkmode-400 dark:border-darkmode-400 btn-primary {{ request()->routeIs('dashboard.cards.create') ? 'btn-primary' : 'text-gray-600 bg-gray-200 dark:bg-dark-1' }}">1</button>
                            <div
                                class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto {{ request()->routeIs('dashboard.cards.create') ? 'font-bold' : 'text-gray-700 dark:text-gray-600' }}">
                                Account Details</div>
                        </div>

                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <button
                                class="w-10 h-10 rounded-full btn text-slate-500 bg-slate-100 dark:bg-darkmode-400 dark:border-darkmode-400 btn-primary {{ request()->routeIs('dashboard.cards.show-card-mode') ? 'btn-primary' : 'text-gray-600 bg-gray-200 dark:bg-dark-1' }}">2</button>
                            <div
                                class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto {{ request()->routeIs('dashboard.cards.show-card-mode') ? 'font-bold' : 'text-gray-700 dark:text-gray-600' }}">
                                Card Selection</div>
                        </div>

                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <button
                                class="w-10 h-10 rounded-full btn text-slate-500 bg-slate-100 dark:bg-darkmode-400 dark:border-darkmode-400 btn-primary {{ request()->routeIs('dashboard.cards.show-card-address') ? 'btn-primary' : 'text-gray-600 bg-gray-200 dark:bg-dark-1' }}">3</button>
                            <div
                                class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto {{ request()->routeIs('dashboard.cards.show-card-address') ? 'font-bold' : 'text-gray-700 dark:text-gray-600' }}">
                                Confirm Address</div>
                        </div>

                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <button
                                class="w-10 h-10 rounded-full btn text-slate-500 bg-slate-100 dark:bg-darkmode-400 dark:border-darkmode-400 btn-primary {{ request()->routeIs('dashboard.cards.show-card-detail') ? 'btn-primary' : 'text-gray-600 bg-gray-200 dark:bg-dark-1' }}">4</button>
                            <div
                                class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto {{ request()->routeIs('dashboard.cards.show-card-detail') ? 'font-bold' : 'text-gray-700 dark:text-gray-600' }}">
                                Confirm Details</div>
                        </div>

                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <button
                                class="w-10 h-10 rounded-full btn text-slate-500 bg-slate-100 dark:bg-darkmode-400 dark:border-darkmode-400 btn-primary {{ request()->routeIs('dashboard.cards.show-card-finalize') ? 'btn-primary' : 'text-gray-600 bg-gray-200 dark:bg-dark-1' }}">5</button>
                            <div
                                class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto {{ request()->routeIs('dashboard.cards.show-card-finalize') ? 'font-bold' : 'text-gray-700 dark:text-gray-600' }}">
                                Finalize</div>
                        </div>

                        <div class="wizard__line hidden lg:block w-3/4 xl:w-4/5 bg-gray-200 dark:bg-dark-1 absolute mt-5">
                        </div>
                    </div>

                    @yield('card-content')
                </div>

                @includeWhen($workspace->status == \Kanexy\PartnerFoundation\Workspace\Enums\WorkspaceStatus::INACTIVE,
                    'partner-foundation::core.inactive-account-alert')
            </div>
        </div>
    </div>
@endsection
