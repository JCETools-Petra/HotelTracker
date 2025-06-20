<x-app-layout>
    @php
        // Definisikan warna di sini agar bisa diakses oleh HTML dan JavaScript
        $chartColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                    <div class="flex-1">
                        <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Properti</label>
                        <select name="property_id" id="property_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Semua Properti</option>
                            @foreach($allPropertiesForFilter as $property)
                                <option value="{{ $property->id }}" {{ $propertyId == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-shrink-0">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode</label>
                        <div class="mt-1 flex rounded-md shadow-sm filter-button-group">
                            <button type="submit" name="period" value="today" class="filter-button rounded-l-md {{ $period == 'today' ? 'active' : '' }}">Hari Ini</button>
                            <button type="submit" name="period" value="month" class="filter-button -ml-px {{ $period == 'month' ? 'active' : '' }}">Bulan Ini</button>
                            <button type="submit" name="period" value="year" class="filter-button -ml-px {{ $period == 'year' ? 'active' : '' }}">Tahun Ini</button>
                            <a href="{{ route('admin.dashboard') }}" class="filter-button -ml-px rounded-r-md">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pendapatan Keseluruhan (Periode: {{ Str::title($period) }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan</h4>
                        <div class="flex flex-col md:flex-row items-center gap-4" style="min-height: 300px;">
                            <div class="w-full md:w-1/2">
                                <canvas id="overallSourcePieChart"></canvas>
                            </div>
                            <div class="w-full md:w-1/2 space-y-1">
                                @php
                                    $pieData = collect($incomeCategories)->map(function($label, $key) use ($overallIncomeSource) {
                                        return ['label' => $label, 'value' => $overallIncomeSource['total_' . $key] ?? 0];
                                    });
                                @endphp
                                @foreach($pieData as $item)
                                    @if($item['value'] > 0)
                                        <div class="flex items-center p-1 rounded">
                                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $chartColors[$loop->index % count($chartColors)] }};"></span>
                                            <div class="flex justify-between items-center w-full text-xs">
                                                <span class="text-gray-600 dark:text-gray-400 mr-2 truncate" title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                                                <span class="font-semibold text-gray-800 dark:text-gray-200 text-right whitespace-nowrap">Rp {{ number_format($item['value'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div style="height: 300px;">
                            <canvas id="overallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Properti</h3>
                    @if(!$properties->isEmpty() && isset($properties->first()->total_income_records))
                    <div class="flex space-x-2">
                        {{-- Route untuk export sekarang menggunakan request()->query() untuk membawa filter --}}
                        <a href="{{ route('admin.dashboard.export.excel', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export Excel</a>
                        <a href="{{ route('admin.dashboard.export.csv', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export CSV</a>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($properties as $property)
                        @include('admin.properties._property_card', ['incomeCategories' => $incomeCategories])
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400">Tidak ada data properti yang ditemukan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDarkMode = document.documentElement.classList.contains('dark');
    Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
    Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

    const overallIncomeSourceData = @json($overallIncomeSource);
    const overallIncomeByPropertyData = @json($overallIncomeByProperty);
    const incomeCategories = @json($incomeCategories);
    const chartColors = @json($chartColors);

    // 1. Diagram Pie
    const overallSourceCanvas = document.getElementById('overallSourcePieChart');
    if (overallSourceCanvas) {
        const pieLabels = Object.values(incomeCategories);
        const pieData = overallIncomeSourceData ? Object.keys(incomeCategories).map(key => overallIncomeSourceData['total_' + key] || 0) : [];
        const hasPieData = pieData.some(v => v > 0);

        if (hasPieData) {
            new Chart(overallSourceCanvas, {
                type: 'pie',
                data: { labels: pieLabels, datasets: [{ data: pieData, backgroundColor: chartColors, }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        } else {
            const container = overallSourceCanvas.parentElement.parentElement;
            container.innerHTML = `<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400 p-4 border dark:border-gray-700 rounded-lg" style="min-height: 300px;">Tidak ada data untuk filter ini.</div>`;
        }
    }

    // 2. Diagram Bar
    const overallIncomeByPropertyCanvas = document.getElementById('overallIncomeByPropertyBarChart');
    if (overallIncomeByPropertyCanvas) {
        if (overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0) {
            // [DIUBAH] Ambil data warna dari properti
            const propertyColors = overallIncomeByPropertyData.map(p => p.chart_color || '#36A2EB');

            new Chart(overallIncomeByPropertyCanvas, {
                type: 'bar',
                data: {
                    labels: overallIncomeByPropertyData.map(p => p.name),
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),
                        backgroundColor: propertyColors, // Gunakan warna dari properti
                        borderColor: propertyColors, // Gunakan warna dari properti
                        borderWidth: 1
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, 
                    scales: { y: { beginAtZero: true } }, 
                    plugins: { legend: { display: false } } 
                }
            });
        } else {
            const container = overallIncomeByPropertyCanvas.parentElement;
            container.innerHTML = `<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">Tidak ada data pendapatan untuk filter ini.</div>`;
        }
    }
});
</script>
@endpush
</x-app-layout>