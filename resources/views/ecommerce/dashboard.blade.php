<x-app-layout>
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
        </div>
    </div>
</x-app-layout>