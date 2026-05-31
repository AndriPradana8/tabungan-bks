@props(['setoran' => '[]', 'tarikan' => '[]'])

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6"
    x-data="{
        setoran: {{ $setoran }},
        tarikan: {{ $tarikan }},
        init() {
            const chartOptions = {
                series: [{
                    name: 'Setoran',
                    data: this.setoran,
                },
                {
                    name: 'Penarikan',
                    data: this.tarikan,
                }],
                legend: { show: false, position: 'top', horizontalAlign: 'left' },
                colors: ['#10B981', '#EF4444'], // Green for setoran, Red for penarikan
                chart: {
                    fontFamily: 'Outfit, sans-serif',
                    height: 310,
                    type: 'area',
                    toolbar: { show: false },
                },
                fill: {
                    gradient: { enabled: true, opacityFrom: 0.55, opacityTo: 0 },
                },
                stroke: { curve: 'straight', width: ['2', '2'] },
                markers: { size: 0 },
                labels: { show: false, position: 'top' },
                grid: {
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                },
                dataLabels: { enabled: false },
                tooltip: {
                    y: { formatter: function (val) { return 'Rp ' + val.toLocaleString('id-ID') } }
                },
                xaxis: {
                    type: 'category',
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    title: { style: { fontSize: '0px' } },
                    labels: { formatter: function (val) { 
                        if(val >= 1000000) return 'Rp ' + (val/1000000) + 'M';
                        if(val >= 1000) return 'Rp ' + (val/1000) + 'K';
                        return 'Rp ' + val;
                    } }
                },
            };

            const chart = new ApexCharts(this.$refs.chartArea, chartOptions);
            chart.render();
        }
    }"
>
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistik Transaksi
            </h3>
            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Statistik Transaksi Setor dan Tarik Tahun {{ date('Y') }}
            </p>
        </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div x-ref="chartArea" class="-ml-4 min-w-[700px] pl-2 xl:min-w-full"></div>
    </div>
</div>
