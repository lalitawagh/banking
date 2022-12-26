
        <div class="intro-y col-span-12 md:col-span-4 lg:col-span-4 intro-y h-full ">
            <div class="box shadow-lg p-2 h-full">
                <div class=" text-lg font-medium mr-auto mt-2">
                    Account
                </div>
                <div style="height: 200px;">
                    <canvas id="account_bar_chart"></canvas>
                </div>
            </div>
        </div>

        <div class="intro-y col-span-12 md:col-span-4 lg:col-span-4 intro-y h-full flex flex-col justify-between">
            <div class="intro-y mb-3 h-full">
                <div class="box shadow-lg text-xl text-center py-3 h-full">
                    <div class="font-bold leading-8 mt-2">{{ $totalUser }}</div>
                    <div class="text-lg font-medium mr-auto mt-2">Total Registration</div>
                </div>
            </div>
            <div class="intro-y h-full">
                <div class="box shadow-lg text-xl text-center py-3 h-full">
                    <div class="font-bold leading-8 mt-2">{{ $totalCards }}</div>
                    <div class="text-lg font-medium mr-auto mt-2">Total Cards</div>
                </div>
            </div>
        </div>



@push('scripts')
<script>
    const labelsBarChart = [
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
    const dataBarChart = {
        labels: labelsBarChart,
        datasets: [{
        label: 'MEMBERSHIP',
        backgroundColor: '#002366',
        borderColor: '#002366',
        data: {!! json_encode($memberships) !!}
        }]
    };
    const configBarChart = {
        type: 'bar',
        data: dataBarChart,
        options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5,
                                maxTicksLimit: 11
                            }
                        }],
                        xAxes: [{
                            barThickness: 6,  // number (pixels) or 'flex'
                            maxBarThickness: 8 // number (pixels)
                        }]
                    }
                }
    };

    var membership_bar_chart = new Chart(
        document.getElementById('membership_bar_chart'),
        configBarChart
    );
</script>
<script>

    const dataDoughnut = {
        labels:{!! json_encode($users->pluck("label")) !!},
        datasets: [{
            label: 'Membership',
            backgroundColor: [
                '#4baef1',
                '#002366',
            ],
            data: {!! json_encode($users->pluck("data")) !!},
            }]
    };
    const configDoughnut = {
        type: 'doughnut',
        data: dataDoughnut,
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    };

    var membership_bar_chart = new Chart(
        document.getElementById('account_bar_chart'),
            configDoughnut
        );

</script>
@endpush




