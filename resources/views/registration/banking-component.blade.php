<div class="r-box1 intro-y blog col-span-12 sm:col-span-3 box sm:mr-2 mb-2">
                <div class="blog__preview"><img alt="" class="rounded-t-md" src="{{ asset('dist/images/r-box1.png') }}">
                </div>

                <div class="px-5 pt-3 pb-5">
                    <h2 class="intro-y font-medium text-xl text-white sm:text-2xl">
                        Open Bank Account
                    </h2>
                    <div
                        class="w-full leading-relaxed text-white text-opacity-70 dark:text-gray-600 dark:text-opacity-100 mt-3">
                        <div class="mt-5">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-white rounded-full mr-3"></div>
                                <span class="">Business and personal accounts with exceptional debit card
                                    features.</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-white rounded-full mr-3"></div>
                                <span class="">Multi currency bank accounts.</span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-white rounded-full mr-3"></div>
                                <span class="">Sort code and account number with IBAN.</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap lg:flex-nowrap items-center justify-center p-5">
                        <a id="bankAccountGo" href="{{ route('customer.signup.create', ['type' => 'business']) }}"
                            class="d-block btn w-32 bg-white btn-rounded-secondary dark:bg-dark-2 dark:text-white mt-6 sm:mt-10 m-auto text-center">GO</a>
                    </div>
                </div>
            </div>