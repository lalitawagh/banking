<div class="intro-y col-span-12 lg:col-span-8 sm:col-span-6">
    <livewire:transaction-graph-dashboard />
</div>

@if (auth()->user()->isSubscriber())

    <div class="intro-y col-span-12 sm:col-span-6 lg:col-span-4">
        <div class="box shadow-lg box p-2 h-auto">
            <div class="text-lg font-medium mr-auto p-2">Useful Information</div>
            @if (auth()->user()->workspaces()->first()->status!=\Kanexy\PartnerFoundation\Core\Enums\WorkspaceStatus::ACTIVE)
            <p class="text-lg active-clr p-2">
                Congratulations!!! Your account is created successfully. Get started with receiving amount to your account and please wait till your account is active to use all the remaining functionalities.
            </p>
            @else
            <p class="text-lg active-clr p-2">
                Congratulations!!! Your account is
                active successfully. Please get started. </p>
            @endif
        </div>

        <div class="col-span-12 lg:col-span-4 mt-4">
            <livewire:total-transaction-dashboard />
        </div>
    </div>

@endif


@push('scripts')
    <!-- Chart line -->
    <script>
        window.addEventListener('UpdateTransactionChart', event => {
            transactionChart();
        });

        function transactionChart(){
            const labels = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            var creditChartTransaction = document.getElementById("updateCredit").innerHTML;
            var debitChartTransaction = document.getElementById("updateDebit").innerHTML;
            @if(session()->get('dark_mode'))
                color = '#316395';
            @else
                color = '#002366';
            @endif
            const data = {
                labels: labels,
                datasets: [{
                    label: 'PAID IN',
                    fill: false,
                    borderColor: color, // Add custom color border (Line)
                    data: JSON.parse(creditChartTransaction),
                },
                {
                    label: 'PAID OUT',
                    fill: false,
                    borderColor: '#4baef1', // Add custom color border (Line)
                    data: JSON.parse(debitChartTransaction),
                }]
            };

            const configLineChart = {
                type: 'line',
                data,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5,
                                maxTicksLimit: 11
                            },
                            responsive: true, // Instruct chart js to respond nicely.
                            maintainAspectRatio: true, // Add to prevent default behaviour of full-width/height

                        }],
                    },
                    y: {
                        suggestedMin: 50,
                        suggestedMax: 100
                    },
                    parsing: {
                        xAxisKey: "month",
                        yAxisKey: "total"
                    },
                    responsive: true, // Instruct chart js to respond nicely.
                    maintainAspectRatio: true // Add to prevent default behaviour of full-width/height
                }
            };

            var report_line_chart_data = document.getElementById("chartLine").getContext('2d');
            var chartLine = new Chart(
                report_line_chart_data,
                configLineChart
            );
        }

        $(function() {
            transactionChart();
        });
    </script>
@endpush
