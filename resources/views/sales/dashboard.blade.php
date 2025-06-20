<x-sales-layout>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-6">
        {{ __('Sales Dashboard') }}
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Booking (Bulan Ini)</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalBookingsThisMonth }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Pasti (Bulan Ini)</h3>
            <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-500">{{ $confirmedBookingsThisMonth }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Pendapatan (Bulan Ini)</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($estimatedRevenueThisMonth, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peserta (Bulan Ini)</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalPaxThisMonth, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Jadwal Event (7 Hari ke Depan)</h3>
            <div class="space-y-4">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 p-2 rounded-md text-center">
                            <p class="text-sm font-bold text-green-700 dark:text-green-300">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $event->client_name }} - {{ $event->event_type }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Pukul {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} di {{ $event->property->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada jadwal event dalam 7 hari ke depan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Booking Terbaru</h3>
            <div class="space-y-3">
                 @forelse($recentBookings as $booking)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $booking->client_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Dibuat pada {{ $booking->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($booking->status == 'Booking Pasti') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($booking->status == 'Booking Sementara') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                            {{ $booking->status }}
                        </span>
                    </div>
                 @empty
                    <p class="text-gray-500 dark:text-gray-400">Belum ada booking yang dibuat.</p>
                 @endforelse
            </div>
        </div>
    </div>
</x-sales-layout>