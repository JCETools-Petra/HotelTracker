<x-app-layout>
    <x-slot name="header">
        {{-- Menggunakan flex untuk menata judul dan navigasi --}}
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>

            {{-- Bagian Navigasi Admin --}}
            <nav class="flex space-x-4">
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.properties.compare.form')" :active="request()->routeIs('admin.properties.compare.form') || request()->routeIs('admin.properties.compare.results')">
                    {{ __('Bandingkan Properti') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pendapatan Keseluruhan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan (Semua Properti)</h4>
                        <div style="height: 300px;">
                            <canvas id="overallSourcePieChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div style="height: 300px;">
                            <canvas id="overallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Bagian Daftar Properti dan Tombol Export --}}
                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Properti</h3>
                    {{-- !! TOMBOL EXPORT ADMIN DITAMBAHKAN DI SINI !! --}}
                    @if(!$properties->isEmpty())
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.dashboard.export.excel') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export Excel
                        </a>
                        <a href="{{ route('admin.dashboard.export.csv') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export CSV
                        </a>
                    </div>
                    @endif
                    {{-- !! AKHIR TOMBOL EXPORT ADMIN !! --}}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($properties as $property)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $property->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Catatan: {{ $property->total_income_records }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">MICE: Rp {{ number_format($property->total_mice_income ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">F&B: Rp {{ number_format($property->total_fnb_income ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">K. Offline: Rp {{ number_format($property->total_offline_room_income ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">K. Online: Rp {{ number_format($property->total_online_room_income ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Lainnya: Rp {{ number_format($property->total_others_income ?? 0, 0, ',', '.') }}</p>
                            <a href="{{ route('admin.properties.show', $property) }}" class="inline-block mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Detail</a>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada properti yang ditemukan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@push('scripts')
{{-- ... (skrip JavaScript Anda untuk chart tetap sama) ... --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin Dashboard DOMContentLoaded'); // Debugging

    const overallIncomeSourceData = @json($overallIncomeSource);
    const overallIncomeByPropertyData = @json($overallIncomeByProperty);

    console.log('Overall Income Source Data:', overallIncomeSourceData);
    console.log('Overall Income By Property Data:', overallIncomeByPropertyData);

    // 1. Diagram Pie: Distribusi Sumber Pendapatan Keseluruhan
    const overallSourceCanvas = document.getElementById('overallSourcePieChart');
    if (overallSourceCanvas) {
        if (overallIncomeSourceData && (overallIncomeSourceData.total_mice || overallIncomeSourceData.total_fnb || overallIncomeSourceData.total_offline_room || overallIncomeSourceData.total_online_room || overallIncomeSourceData.total_others)) {
            console.log('Rendering Overall Source Pie Chart');
            const overallSourceCtx = overallSourceCanvas.getContext('2d');
            new Chart(overallSourceCtx, {
                type: 'pie',
                data: {
                    labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                    datasets: [{
                        label: 'Distribusi Pendapatan',
                        data: [
                            overallIncomeSourceData.total_mice || 0,
                            overallIncomeSourceData.total_fnb || 0,
                            overallIncomeSourceData.total_offline_room || 0,
                            overallIncomeSourceData.total_online_room || 0,
                            overallIncomeSourceData.total_others || 0
                        ],
                        backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, title: { display: true, text: 'Distribusi Sumber Pendapatan (Semua Properti)' } } }
            });
        } else {
            console.log('No data for Overall Source Pie Chart or canvas not found.');
            const ctx = overallSourceCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Sesuaikan warna dengan tema
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data sumber pendapatan keseluruhan.', overallSourceCanvas.width / 2, overallSourceCanvas.height / 2);
        }
    } else {
        console.error('Canvas element with ID "overallSourcePieChart" not found.');
    }

    // 2. Diagram Bar: Total Pendapatan per Properti
    const overallIncomeByPropertyCanvas = document.getElementById('overallIncomeByPropertyBarChart');
    if (overallIncomeByPropertyCanvas) {
        if (overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0) {
            console.log('Rendering Overall Income By Property Bar Chart');
            const propertyLabels = overallIncomeByPropertyData.map(p => p.name);
            const propertyRevenues = overallIncomeByPropertyData.map(p => p.total_revenue || 0);
            const overallIncomeByPropertyCtx = overallIncomeByPropertyCanvas.getContext('2d');
            new Chart(overallIncomeByPropertyCtx, {
                type: 'bar',
                data: {
                    labels: propertyLabels,
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: propertyRevenues,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } } }, plugins: { legend: { display: false }, title: { display: true, text: 'Total Pendapatan per Properti' } } }
            });
        } else {
            console.log('No data for Overall Income By Property Bar Chart or canvas not found.');
            const ctx = overallIncomeByPropertyCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data pendapatan per properti.', overallIncomeByPropertyCanvas.width / 2, overallIncomeByPropertyCanvas.height / 2);
        }
    } else {
        console.error('Canvas element with ID "overallIncomeByPropertyBarChart" not found.');
    }
});
</script>
@endpush
</x-app-layout>