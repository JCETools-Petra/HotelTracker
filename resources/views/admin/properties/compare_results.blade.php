<<<<<<< HEAD
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hasil Perbandingan Properti') }}
            </h2>
            <nav>
                <x-nav-link :href="route('admin.properties.compare.form')" class="ml-3">
                    {{ __('Buat Perbandingan Baru') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard')" class="ml-3">
                    {{ __('Kembali ke Dashboard Admin') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Kriteria Perbandingan:</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Properti:</strong>
                            @foreach ($selectedPropertiesModels as $prop)
                                {{ $prop->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Periode:</strong> {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>
                    </div>

                    {{-- Chart Perbandingan Kategori Pendapatan (Grouped Bar Chart) --}}
                    <div class="mb-8 p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Grafik Perbandingan Pendapatan per Kategori</h4>
                        <div style="height: 450px;">
                            <canvas id="propertiesCategoryComparisonChart"></canvas> {{-- ID diubah untuk kejelasan --}}
                        </div>
                    </div>

                    {{-- BARU: Chart Perbandingan Tren Pendapatan Harian (Multi-Line Chart) --}}
                    <div class="mb-8 p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Grafik Perbandingan Tren Pendapatan Harian</h4>
                        <div style="height: 450px;">
                            <canvas id="propertiesTrendComparisonChart"></canvas>
                        </div>
                    </div>
                    {{-- AKHIR BAGIAN BARU --}}


                    {{-- Tabel Data Perbandingan Detail --}}
                    @if (!empty($comparisonData))
                        <h4 class="text-lg font-semibold mt-8 mb-4">Detail Data Pendapatan (Rp):</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Properti</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Offline</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Online</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($comparisonData as $data)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $data['name'] }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['mice_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['fnb_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['offline_room_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['online_room_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['others_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan ditemukan untuk kriteria yang dipilih.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari Controller
    const chartDataGroupedBarPHP = @json($chartDataGroupedBar); // Untuk perbandingan kategori
    const trendChartDataPHP = @json($trendChartData);       // Untuk perbandingan tren harian

    console.log('Grouped Bar Chart Data (Categories):', chartDataGroupedBarPHP);
    console.log('Trend Line Chart Data (Daily):', trendChartDataPHP);

    // 1. Grafik Batang Terkelompok: Perbandingan Pendapatan per Kategori
    const categoryComparisonCanvas = document.getElementById('propertiesCategoryComparisonChart');
    if (categoryComparisonCanvas && chartDataGroupedBarPHP && chartDataGroupedBarPHP.labels && chartDataGroupedBarPHP.labels.length > 0 && chartDataGroupedBarPHP.datasets && chartDataGroupedBarPHP.datasets.length > 0) {
        console.log('Rendering Properties Category Comparison (Grouped Bar) Chart');
        const categoryCtx = categoryComparisonCanvas.getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: chartDataGroupedBarPHP, // Data sudah dalam format yang benar dari controller
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } }
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Perbandingan Pendapatan Properti per Kategori' },
                    tooltip: { callbacks: { label: function(context) { let label = context.dataset.label || ''; if (label) { label += ': '; } if (context.parsed.y !== null) { label += 'Rp ' + context.parsed.y.toLocaleString('id-ID'); } return label; } } }
                }
            }
        });
    } else if (categoryComparisonCanvas) {
        console.log('No data for Properties Category Comparison Chart.');
        const ctx = categoryComparisonCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data untuk chart perbandingan kategori.', categoryComparisonCanvas.width / 2, categoryComparisonCanvas.height / 2);
    } else {
        console.error('Canvas element with ID "propertiesCategoryComparisonChart" not found.');
    }

    // 2. Grafik Garis: Perbandingan Tren Pendapatan Harian
    const trendComparisonCanvas = document.getElementById('propertiesTrendComparisonChart');
    if (trendComparisonCanvas && trendChartDataPHP && trendChartDataPHP.labels && trendChartDataPHP.labels.length > 0 && trendChartDataPHP.datasets && trendChartDataPHP.datasets.length > 0) {
        console.log('Rendering Properties Trend Comparison (Line) Chart');
        const trendCtx = trendComparisonCanvas.getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: trendChartDataPHP, // Data sudah dalam format yang benar dari controller
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: { // Sumbu X adalah tanggal
                        ticks: {
                            // autoSkip: true,
                            // maxTicksLimit: 15 // Batasi jumlah label tanggal jika terlalu banyak
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Tren Pendapatan Harian Properti'
                    },
                    tooltip: {
                        mode: 'index', // Tampilkan tooltip untuk semua dataset pada titik yang sama
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                interaction: { // Untuk performa tooltip yang lebih baik pada banyak data
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    } else if (trendComparisonCanvas) {
        console.log('No data for Properties Trend Comparison Chart.');
        const ctx = trendComparisonCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data untuk chart perbandingan tren.', trendComparisonCanvas.width / 2, trendComparisonCanvas.height / 2);
    } else {
        console.error('Canvas element with ID "propertiesTrendComparisonChart" not found.');
    }
});
</script>
@endpush
=======
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hasil Perbandingan Properti') }}
            </h2>
            <nav>
                <x-nav-link :href="route('admin.properties.compare.form')" class="ml-3">
                    {{ __('Buat Perbandingan Baru') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.dashboard')" class="ml-3">
                    {{ __('Kembali ke Dashboard Admin') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Kriteria Perbandingan:</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Properti:</strong>
                            @foreach ($selectedPropertiesModels as $prop)
                                {{ $prop->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Periode:</strong> {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>
                    </div>

                    {{-- Chart Perbandingan Kategori Pendapatan (Grouped Bar Chart) --}}
                    <div class="mb-8 p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Grafik Perbandingan Pendapatan per Kategori</h4>
                        <div style="height: 450px;">
                            <canvas id="propertiesCategoryComparisonChart"></canvas> {{-- ID diubah untuk kejelasan --}}
                        </div>
                    </div>

                    {{-- BARU: Chart Perbandingan Tren Pendapatan Harian (Multi-Line Chart) --}}
                    <div class="mb-8 p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Grafik Perbandingan Tren Pendapatan Harian</h4>
                        <div style="height: 450px;">
                            <canvas id="propertiesTrendComparisonChart"></canvas>
                        </div>
                    </div>
                    {{-- AKHIR BAGIAN BARU --}}


                    {{-- Tabel Data Perbandingan Detail --}}
                    @if (!empty($comparisonData))
                        <h4 class="text-lg font-semibold mt-8 mb-4">Detail Data Pendapatan (Rp):</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Properti</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Offline</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Online</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($comparisonData as $data)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $data['name'] }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['mice_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['fnb_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['offline_room_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['online_room_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data['others_income'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan ditemukan untuk kriteria yang dipilih.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari Controller
    const chartDataGroupedBarPHP = @json($chartDataGroupedBar); // Untuk perbandingan kategori
    const trendChartDataPHP = @json($trendChartData);       // Untuk perbandingan tren harian

    console.log('Grouped Bar Chart Data (Categories):', chartDataGroupedBarPHP);
    console.log('Trend Line Chart Data (Daily):', trendChartDataPHP);

    // 1. Grafik Batang Terkelompok: Perbandingan Pendapatan per Kategori
    const categoryComparisonCanvas = document.getElementById('propertiesCategoryComparisonChart');
    if (categoryComparisonCanvas && chartDataGroupedBarPHP && chartDataGroupedBarPHP.labels && chartDataGroupedBarPHP.labels.length > 0 && chartDataGroupedBarPHP.datasets && chartDataGroupedBarPHP.datasets.length > 0) {
        console.log('Rendering Properties Category Comparison (Grouped Bar) Chart');
        const categoryCtx = categoryComparisonCanvas.getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: chartDataGroupedBarPHP, // Data sudah dalam format yang benar dari controller
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } }
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Perbandingan Pendapatan Properti per Kategori' },
                    tooltip: { callbacks: { label: function(context) { let label = context.dataset.label || ''; if (label) { label += ': '; } if (context.parsed.y !== null) { label += 'Rp ' + context.parsed.y.toLocaleString('id-ID'); } return label; } } }
                }
            }
        });
    } else if (categoryComparisonCanvas) {
        console.log('No data for Properties Category Comparison Chart.');
        const ctx = categoryComparisonCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data untuk chart perbandingan kategori.', categoryComparisonCanvas.width / 2, categoryComparisonCanvas.height / 2);
    } else {
        console.error('Canvas element with ID "propertiesCategoryComparisonChart" not found.');
    }

    // 2. Grafik Garis: Perbandingan Tren Pendapatan Harian
    const trendComparisonCanvas = document.getElementById('propertiesTrendComparisonChart');
    if (trendComparisonCanvas && trendChartDataPHP && trendChartDataPHP.labels && trendChartDataPHP.labels.length > 0 && trendChartDataPHP.datasets && trendChartDataPHP.datasets.length > 0) {
        console.log('Rendering Properties Trend Comparison (Line) Chart');
        const trendCtx = trendComparisonCanvas.getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: trendChartDataPHP, // Data sudah dalam format yang benar dari controller
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: { // Sumbu X adalah tanggal
                        ticks: {
                            // autoSkip: true,
                            // maxTicksLimit: 15 // Batasi jumlah label tanggal jika terlalu banyak
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Tren Pendapatan Harian Properti'
                    },
                    tooltip: {
                        mode: 'index', // Tampilkan tooltip untuk semua dataset pada titik yang sama
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                interaction: { // Untuk performa tooltip yang lebih baik pada banyak data
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    } else if (trendComparisonCanvas) {
        console.log('No data for Properties Trend Comparison Chart.');
        const ctx = trendComparisonCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data untuk chart perbandingan tren.', trendComparisonCanvas.width / 2, trendComparisonCanvas.height / 2);
    } else {
        console.error('Canvas element with ID "propertiesTrendComparisonChart" not found.');
    }
});
</script>
@endpush
>>>>>>> d022ff7944e4652039483fc40f98e16fe7417648
</x-app-layout>