<x-app-layout>
    @push('styles')
    <style>
        #calendar { min-height: 60vh; }
    </style>
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Harga OTA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                Anda memantau harga untuk properti: 
                                <span class="font-semibold text-indigo-500">{{ $property->name }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-green-700 dark:text-green-300">Okupansi Saat Ini</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $currentOccupancy }}
                                <span class="text-xl font-medium text-gray-500">Kamar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            Harga Kamar OTA Saat Ini
                        </h4>
                        
                        @if($currentPrices->isEmpty())
                            <p class="text-center text-gray-500 py-8">Belum ada tipe kamar yang dikonfigurasi untuk properti ini. Hubungi Admin.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Tipe Kamar
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Harga OTA Seharusnya
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($currentPrices as $priceInfo)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $priceInfo['name'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-gray-100">
                                                    Rp {{ number_format($priceInfo['price_ota'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                         <p class="mt-6 text-xs text-gray-500 dark:text-gray-400 text-center">
                            *Harga dihitung berdasarkan Bottom Rate, Persentase Kenaikan, dan Okupansi yang diinput oleh Pengguna Properti.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-xl font-semibold mb-4">Reservasi Saya</h4>
<div class="mt-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-xl font-semibold mb-4">Reservasi Saya</h4>

                        @if(session('success'))
                            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                        @endif

                        <a href="{{ route('ecommerce.reservations.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded">Tambah Reservasi</a>

                        @if($reservations->isNotEmpty())
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tamu</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kamar</th>
                                            <th class="px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($reservations as $reservation)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->guest_name }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->checkin_date }} - {{ $reservation->checkout_date }}</td>
                                                <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-gray-100">{{ $reservation->number_of_rooms }}</td>
                                                <td class="px-4 py-2 text-sm text-right">
                                                    <a href="{{ route('ecommerce.reservations.edit', $reservation) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                                    <form action="{{ route('ecommerce.reservations.destroy', $reservation) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus reservasi ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mb-6 text-sm text-gray-500">Belum ada reservasi yang Anda buat.</p>
                        @endif

                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '{{ route('ecommerce.reservations.events') }}',
                eventClick: function(info) {
                    window.location.href = '{{ url('ecommerce/reservations') }}/' + info.event.id + '/edit';
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>