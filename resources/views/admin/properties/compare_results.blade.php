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
                            <canvas id="propertiesCategoryComparisonChart"></canvas>
                        </div>
                    </div>

                    {{-- Chart Perbandingan Tren Pendapatan Harian (Multi-Line Chart) --}}
                    <div class="mb-8 p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Grafik Perbandingan Tren Pendapatan Harian</h4>
                        <div style="height: 450px;">
                            <canvas id="propertiesTrendComparisonChart"></canvas>
                        </div>
                    </div>
                    
                    {{-- Tabel Data Perbandingan Detail --}}
                    @if (!empty($comparisonData))
                        <h4 class="text-lg font-semibold mt-8 mb-4">Detail Data Pendapatan (Rp):</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Properti</th>
                                        {{-- Loop untuk membuat header tabel dinamis --}}
                                        @foreach($incomeCategories as $label)
                                             <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $label }}</th>
                                        @endforeach
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($comparisonData as $data)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $data['name'] }}</td>
                                        {{-- Loop untuk menampilkan data setiap kategori --}}
                                        @foreach($incomeCategories as $column => $label)
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($data[$column] ?? 0, 0, ',', '.') }}</td>
                                        @endforeach
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ number_format($data['total_revenue'] ?? 0, 0, ',', '.') }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartDataGroupedBarPHP = @json($chartDataGroupedBar);
    const trendChartDataPHP = @json($trendChartData);

    // 1. Grafik Batang Terkelompok: Perbandingan Pendapatan per Kategori
    const categoryComparisonCanvas = document.getElementById('propertiesCategoryComparisonChart');
    if (categoryComparisonCanvas && chartDataGroupedBarPHP && chartDataGroupedBarPHP.datasets.length > 0) {
        new Chart(categoryComparisonCanvas, {
            type: 'bar',
            data: chartDataGroupedBarPHP,
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } } },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Perbandingan Pendapatan Properti per Kategori' },
                    tooltip: { callbacks: { label: context => `${context.dataset.label}: Rp ${context.parsed.y.toLocaleString('id-ID')}` } }
                }
            }
        });
    }

    // 2. Grafik Garis: Perbandingan Tren Pendapatan Harian
    const trendComparisonCanvas = document.getElementById('propertiesTrendComparisonChart');
    if (trendComparisonCanvas && trendChartDataPHP && trendChartDataPHP.datasets.length > 0) {
        new Chart(trendComparisonCanvas, {
            type: 'line',
            data: trendChartDataPHP,
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } } },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Perbandingan Tren Pendapatan Harian Properti' },
                    tooltip: { mode: 'index', intersect: false, callbacks: { label: context => `${context.dataset.label}: Rp ${context.parsed.y.toLocaleString('id-ID')}` } }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });
    }
});
</script>
@endpush
</x-app-layout>