<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Properti') }}
            @if(isset($property) && $property)
                : {{ $property->name }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Selamat Datang di Dashboard Properti Anda!</h3>

                    @if(isset($property) && $property)
                        <p class="mt-2">Anda sedang mengelola properti: <strong>{{ $property->name }}</strong>.</p>
                    @else
                        <p class="mt-2 text-red-600">Informasi properti tidak ditemukan.</p>
                    @endif

                    {{-- ======================= AWAL BLOK YANG DIPERBARUI ======================= --}}
                    @if(isset($todayIncome))
                        <div class="mt-6 p-4 bg-green-100 dark:bg-gray-700/50 rounded-lg border border-green-200 dark:border-gray-600">
                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200">Ringkasan Pendapatan Hari Ini ({{ \Carbon\Carbon::today()->isoFormat('LL') }})</h4>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                                
                                {{-- Pendapatan Kamar --}}
                                <div class="space-y-1">
                                    <p class="font-medium text-gray-600 dark:text-gray-400 border-b border-gray-300 dark:border-gray-600 pb-1 mb-2">Pendapatan Kamar</p>
                                    <p>Walk In: <span class="float-right font-mono">Rp {{ number_format($todayIncome->offline_room_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>OTA: <span class="float-right font-mono">Rp {{ number_format($todayIncome->online_room_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>Travel Agent: <span class="float-right font-mono">Rp {{ number_format($todayIncome->ta_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>Government: <span class="float-right font-mono">Rp {{ number_format($todayIncome->gov_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>Corporation: <span class="float-right font-mono">Rp {{ number_format($todayIncome->corp_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>Mice Room: <span class="float-right font-mono">Rp {{ number_format($todayIncome->mice_room_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p class="font-semibold pt-1 border-t border-dashed border-gray-400">Total Kamar Terjual: <span class="float-right font-mono">{{ $todayIncome->total_rooms_sold ?? 0 }}</span></p>
                                </div>

                                {{-- Pendapatan Lainnya --}}
                                <div class="space-y-1">
                                    <p class="font-medium text-gray-600 dark:text-gray-400 border-b border-gray-300 dark:border-gray-600 pb-1 mb-2">Pendapatan Lainnya</p>
                                    <p>MICE (Non-Kamar): <span class="float-right font-mono">Rp {{ number_format($todayIncome->mice_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>F&B: <span class="float-right font-mono">Rp {{ number_format($todayIncome->fb_income ?? 0, 0, ',', '.') }}</span></p>
                                    <p>Lainnya: <span class="float-right font-mono">Rp {{ number_format($todayIncome->others_income ?? 0, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                            
                            {{-- Total Pendapatan --}}
                            <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                <p class="font-bold text-lg text-gray-800 dark:text-gray-200">Total Pendapatan Hari Ini:</p>
                                <p class="font-extrabold text-2xl text-green-700 dark:text-green-400">Rp {{ number_format($todayIncome->total_revenue ?? 0, 0, ',', '.') }}</p>
                            </div>

                            <a href="{{ route('property.income.edit', $todayIncome->id) }}" class="mt-4 inline-block text-blue-600 hover:underline text-sm">Edit Pendapatan Hari Ini</a>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-100 dark:bg-yellow-700 rounded-lg">
                            <p>Belum ada data pendapatan yang tercatat untuk hari ini.</p>
                            <a href="{{ route('property.income.create') }}" class="mt-2 inline-block text-blue-600 hover:underline font-semibold">Catat Pendapatan Hari Ini</a>
                        </div>
                    @endif
                    {{-- ======================= AKHIR BLOK YANG DIPERBARUI ======================= --}}

                    <div class="mt-6">
                        <a href="{{ route('property.income.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                            Lihat Riwayat Pendapatan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>