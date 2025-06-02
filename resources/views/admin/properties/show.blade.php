{{-- resources/views/admin/properties/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Properti: ') }} {{ $property->name }}
            </h2>
            <nav>
                <x-nav-link :href="route('admin.dashboard')" class="ml-3">
                    {{ __('Kembali ke Dashboard Admin') }}
                </x-nav-link>
                {{-- Anda bisa menambahkan link navigasi lain di sini jika diperlukan, misal "Bandingkan Properti" --}}
                <x-nav-link :href="route('admin.properties.compare.form')" :active="request()->routeIs('admin.properties.compare.form') || request()->routeIs('admin.properties.compare.results')" class="ml-3">
                    {{ __('Bandingkan Properti') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-4">{{ $property->name }}</h3>

                    {{-- !! FORM FILTER TANGGAL UNTUK DETAIL PROPERTI !! --}}
                    <form method="GET" action="{{ route('admin.properties.show', $property->id) }}" class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">Filter Data Berdasarkan Tanggal:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="start_date_filter" :value="__('Dari Tanggal')" />
                                <x-text-input id="start_date_filter" class="block mt-1 w-full" type="date" name="start_date"
                                              :value="request('start_date', $startDate ? $startDate->toDateString() : '')" />
                            </div>
                            <div>
                                <x-input-label for="end_date_filter" :value="__('Sampai Tanggal')" />
                                <x-text-input id="end_date_filter" class="block mt-1 w-full" type="date" name="end_date"
                                              :value="request('end_date', $endDate ? $endDate->toDateString() : '')" />
                            </div>
                            <div class="flex space-x-2 pt-5 md:pt-0">
                                <x-primary-button type="submit">
                                    {{ __('Terapkan Filter') }}
                                </x-primary-button>
                                <a href="{{ route('admin.properties.show', $property->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- !! AKHIR FORM FILTER !! --}}

                    {{-- !! TOMBOL EXPORT DETAIL PROPERTI !! --}}
                    <div class="mb-8 flex flex-wrap gap-2">
                        <a href="{{ route('admin.properties.export.excel', ['property' => $property->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 3.293a1 1 0 011.414 0L10 11.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                            Export Excel
                        </a>
                        <a href="{{ route('admin.properties.export.csv', ['property' => $property->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Export CSV
                        </a>
                    </div>
                    {{-- !! AKHIR TOMBOL EXPORT !! --}}

                    {{-- Bagian Grafik --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Distribusi Pendapatan
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                ({{ $property->name }})
                                @if($startDate && $endDate)
                                    <span class="block sm:inline">Periode: {{ $startDate->isoFormat('D MMM YY') }} - {{ $endDate->isoFormat('D MMM YY') }}</span>
                                @elseif($startDate)
                                    <span class="block sm:inline">Periode: Dari {{ $startDate->isoFormat('D MMM YY') }}</span>
                                @elseif($endDate)
                                    <span class="block sm:inline">Periode: Sampai {{ $endDate->isoFormat('D MMM YY') }}</span>
                                @else
                                     <span class="block sm:inline">(Keseluruhan Data Properti)</span> {{-- Atau sesuaikan jika ada default lain --}}
                                @endif
                            </p>
                            <div style="height: 350px;">
                                 <canvas id="propertySourceDistributionPieChart"></canvas>
                            </div>
                        </div>

                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tren Pendapatan Harian
                            </h4>
                             <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                @if($startDate && $endDate)
                                    <span class="block sm:inline">Periode: {{ $startDate->isoFormat('D MMM YY') }} - {{ $endDate->isoFormat('D MMM YY') }}</span>
                                @elseif($startDate)
                                    <span class="block sm:inline">Periode: Dari {{ $startDate->isoFormat('D MMM YY') }}</span>
                                @elseif($endDate)
                                    <span class="block sm:inline">Periode: Sampai {{ $endDate->isoFormat('D MMM YY') }}</span>
                                @else
                                    <span class="block sm:inline">(Default 30 Entri Terakhir)</span>
                                @endif
                            </p>
                             <div style="height: 350px;">
                                <canvas id="propertyDailyTrendLineChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Judul Tabel Riwayat Pendapatan --}}
                    <h4 class="text-lg font-semibold mt-8 mb-1">
                        Riwayat Pendapatan
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        @if ($startDate && $endDate)
                            Periode: {{ $startDate->isoFormat('D MMMM YY') }} - {{ $endDate->isoFormat('D MMMM YY') }}
                        @elseif ($startDate)
                            Periode: Dari {{ $startDate->isoFormat('D MMMM YY') }}
                        @elseif ($endDate)
                            Periode: Sampai {{ $endDate->isoFormat('D MMMM YY') }}
                        @else
                            (Default berdasarkan pengaturan controller) {{-- Sesuaikan jika defaultnya bukan 30 hari --}}
                        @endif
                    </p>
                    @if($incomes->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">
                            Belum ada data pendapatan yang tercatat untuk properti ini
                            @if($startDate || $endDate)
                                pada periode yang dipilih.
                            @else
                                .
                            @endif
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                      <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar Offline</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar Online</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($incomes as $income)
                                    @php
                                        $totalIncome = ($income->mice_income ?? 0) + ($income->fnb_income ?? 0) + ($income->offline_room_income ?? 0) + ($income->online_room_income ?? 0) + ($income->others_income ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($income->date)->isoFormat('D MMM YY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($income->mice_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($income->fnb_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($income->offline_room_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($income->online_room_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($income->others_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
{{-- Skrip JavaScript untuk Chart.js Anda (tidak berubah dari versi sebelumnya yang sudah robust) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin Property Detail DOMContentLoaded for property: {{ $property->name }}');

    const sourceDistributionData = @json($sourceDistribution);
    const dailyTrendData = @json($dailyTrend);

    console.log('Source Distribution Data (filtered):', sourceDistributionData);
    console.log('Daily Trend Data (filtered):', dailyTrendData);

    // 1. Grafik Pie: Distribusi Sumber Pendapatan
    const propertySourceCanvas = document.getElementById('propertySourceDistributionPieChart');
    if (propertySourceCanvas) {
        if (sourceDistributionData && (parseFloat(sourceDistributionData.total_mice) > 0 || parseFloat(sourceDistributionData.total_fnb) > 0 || parseFloat(sourceDistributionData.total_offline_room) > 0 || parseFloat(sourceDistributionData.total_online_room) > 0 || parseFloat(sourceDistributionData.total_others) > 0)) {
            console.log('Rendering Property Source Distribution Pie Chart');
            const propertySourceCtx = propertySourceCanvas.getContext('2d');
            new Chart(propertySourceCtx, {
                type: 'pie',
                data: { /* ... data chart seperti sebelumnya ... */
                    labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                    datasets: [{
                        label: 'Distribusi Pendapatan {{ $property->name }}',
                        data: [
                            sourceDistributionData.total_mice || 0,
                            sourceDistributionData.total_fnb || 0,
                            sourceDistributionData.total_offline_room || 0,
                            sourceDistributionData.total_online_room || 0,
                            sourceDistributionData.total_others || 0
                        ],
                        backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, title: { display: true, text: 'Distribusi Sumber Pendapatan ({{ $property->name }})' } } }
            });
        } else {
            console.log('No data for Property Source Distribution Pie Chart or all values are zero.');
            const ctx = propertySourceCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data distribusi pendapatan untuk periode ini.', propertySourceCanvas.width / 2, propertySourceCanvas.height / 2);
        }
    } else {
         console.error('Canvas element with ID "propertySourceDistributionPieChart" not found.');
    }

    // 2. Grafik Line: Tren Pendapatan Harian
    const propertyTrendCanvas = document.getElementById('propertyDailyTrendLineChart');
    if (propertyTrendCanvas) {
        if (dailyTrendData && dailyTrendData.length > 0) {
            console.log('Rendering Property Daily Trend Line Chart');
            const trendLabels = dailyTrendData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
            const trendDataset = dailyTrendData.map(item => item.total_daily_income);
            const propertyTrendCtx = propertyTrendCanvas.getContext('2d');
            new Chart(propertyTrendCtx, {
                type: 'line',
                data: { /* ... data chart seperti sebelumnya ... */
                    labels: trendLabels,
                    datasets: [{
                        label: 'Total Pendapatan Harian (Rp)',
                        data: trendDataset,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } } }, plugins: { legend: { display: true, position: 'top' }, title: { display: true, text: 'Tren Total Pendapatan Harian' } } }
            });
        } else {
            console.log('No data for Property Daily Trend Line Chart.');
            const ctx = propertyTrendCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data tren pendapatan harian untuk periode ini.', propertyTrendCanvas.width / 2, propertyTrendCanvas.height / 2);
        }
    } else {
        console.error('Canvas element with ID "propertyDailyTrendLineChart" not found.');
    }
});
</script>
@endpush
</x-app-layout>