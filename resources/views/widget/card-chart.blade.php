@if (auth()->user()->isSuperAdmin())
    <div class="intro-y col-span-12 sm:col-span-6 lg:col-span-4">
        <div class="box shadow-lg box p-2">
            <div class="text-lg font-medium mr-auto">Cards</div>
            <div style="height: 250px;">
                <canvas id="cardPieChart" class="h-full w-full mt-25"></canvas>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 mt-4">
            <livewire:total-transaction-dashboard />
        </div>
    </div>


    @push('scripts')
        <script>
            const dataCardDoughnut = {
                labels: {!! json_encode($cards->pluck('label')) !!},
                datasets: [{
                    label: 'CARD',
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    data: {!! json_encode($cards->pluck('data')) !!},
                }]
            };
            const configCardDoughnut = {
                type: 'doughnut',
                data: dataCardDoughnut,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            };

            var card_pie_chart = new Chart(
                document.getElementById('cardPieChart'),
                configCardDoughnut,
            );
        </script>
    @endpush
@endif
