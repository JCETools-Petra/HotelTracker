<x-property-user-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Selamat datang, {{ Auth::user()->name }}! Anda mengelola properti {{ $property->name }}.
                </div>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Update Okupansi Hari Ini ({{ today()->isoFormat('D MMMM YYYY') }})</h3>
                <form action="{{ route('property.occupancy.update') }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="occupied_rooms" class="text-sm text-gray-700">Jumlah Kamar Terisi Saat Ini:</label>
                            <input type="number" name="occupied_rooms" value="{{ $occupancyToday->occupied_rooms }}" class="mt-1 block rounded-md border-gray-300 shadow-sm">
                        </div>
                        <button type="submit" class="self-end inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                            Update
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6" x-data="{ priceView: 'ota' }">
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            Harga Kamar Saat Ini
                        </h4>
                        <div class="flex items-center space-x-2 rounded-lg bg-gray-200 dark:bg-gray-900 p-1">
                            <button @click="priceView = 'ota'" 
                                    :class="priceView === 'ota' ? 'bg-white dark:bg-gray-700 shadow' : ''"
                                    class="px-4 py-1 text-sm font-semibold rounded-md transition">
                                Harga OTA
                            </button>
                            <button @click="priceView = 'affiliate'" 
                                    :class="priceView === 'affiliate' ? 'bg-white dark:bg-gray-700 shadow' : ''"
                                    class="px-4 py-1 text-sm font-semibold rounded-md transition">
                                Harga Afiliasi
                            </button>
                        </div>
                    </div>
                    
                    @if($currentPrices->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada tipe kamar yang dikonfigurasi. Hubungi Admin.</p>
                    @else
                        <div x-show="priceView === 'ota'" x-transition>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga OTA Seharusnya</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($currentPrices as $priceInfo)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $priceInfo['name'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($priceInfo['price_ota'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div x-show="priceView === 'affiliate'" x-transition style="display: none;">
                            <div class="space-y-6">
                                @foreach($currentPrices as $priceInfo)
                                    @php $breakdown = $priceInfo['price_affiliate_breakdown']; @endphp
                                    <div class="p-4 border rounded-lg dark:border-gray-700">
                                        <h5 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $priceInfo['name'] }}</h5>
                                        <table class="w-full text-sm mt-2">
                                            <tbody class="divide-y dark:divide-gray-700">
                                                <tr>
                                                    <td class="py-2 text-gray-600 dark:text-gray-400">Harga OTA Dinamis</td>
                                                    <td class="py-2 text-right font-medium">Rp {{ number_format($breakdown['initial_ota_price'], 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="py-2 text-gray-600 dark:text-gray-400">Diskon (5%)</td>
                                                    <td class="py-2 text-right font-medium text-red-500">- Rp {{ number_format($breakdown['discount_amount'], 0, ',', '.') }}</td>
                                                </tr>
                                                <tr class="font-semibold">
                                                    <td class="py-2 text-gray-800 dark:text-gray-200">Harga Setelah Diskon</td>
                                                    <td class="py-2 text-right">Rp {{ number_format($breakdown['price_after_discount'], 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="py-2 text-gray-600 dark:text-gray-400">Komisi (10%)</td>
                                                    <td class="py-2 text-right font-medium text-red-500">- Rp {{ number_format($breakdown['commission_amount'], 0, ',', '.') }}</td>
                                                </tr>
                                                <tr class="font-bold text-lg bg-green-50 dark:bg-green-900">
                                                    <td class="p-3 text-green-800 dark:text-green-200">Harga Akhir Afiliasi</td>
                                                    <td class="p-3 text-right text-green-800 dark:text-green-200">Rp {{ number_format($breakdown['final_price'], 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                     <p class="mt-6 text-xs text-gray-500 dark:text-gray-400 text-center">
                        *Harga dihitung berdasarkan Okupansi yang Anda input di atas.
                    </p>
                </div>
            </div>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- (Kartu-kartu ringkasan pendapatan Anda) --}}
            </div>

            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                {{-- (Tabel pendapatan terbaru Anda) --}}
            </div>
        </div>
    </div>
</x-property-user-layout>