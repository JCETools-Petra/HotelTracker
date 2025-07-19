<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-col">
    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-3">{{ $property->name }}</h3>

    <div class="space-y-1 text-gray-600 dark:text-gray-300 flex-grow">
        {{-- Rincian Pendapatan Kamar --}}
        <div class="flex justify-between text-sm">
            <span>Walk In</span>
            <span>Rp {{ number_format($property->total_offline_room_income ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span>OTA</span>
            <span>Rp {{ number_format($property->total_online_room_income ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span>Travel Agent</span>
            <span>Rp {{ number_format($property->total_ta_income ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span>Government</span>
            <span>Rp {{ number_format($property->total_gov_income ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span>Corporation</span>
            <span>Rp {{ number_format($property->total_corp_income ?? 0, 0, ',', '.') }}</span>
        </div>
        
        <div class="flex justify-between text-sm">
            <span>Afiliasi</span>
            <span>Rp {{ number_format($property->total_afiliasi_income ?? 0, 0, ',', '.') }}</span>
        </div>

        {{-- Rincian Pendapatan F&B --}}
        <div class="pt-2">
            <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">Pendapatan F&B</p>
            <div class="pl-2">
                <div class="flex justify-between text-sm">
                    <span>Breakfast</span>
                    <span>Rp {{ number_format($property->total_breakfast_income ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Lunch</span>
                    <span>Rp {{ number_format($property->total_lunch_income ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Dinner</span>
                    <span>Rp {{ number_format($property->total_dinner_income ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Rincian Pendapatan Lainnya --}}
        @if(isset($property->mice_revenue_breakdown) && $property->mice_revenue_breakdown->isNotEmpty())
            <div class="pt-2">
                <p class="font-semibold text-sm text-gray-700 dark:text-gray-200">Pendapatan MICE (Luar Sales)</p>
                <div class="pl-2">
                    @foreach($property->mice_revenue_breakdown as $breakdown)
                        <div class="flex justify-between text-sm">
                            <span>{{ $breakdown->miceCategory->name ?? 'Lainnya' }}</span>
                            <span>Rp {{ number_format($breakdown->total_mice_revenue ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-between text-sm pt-2">
            <span>Lainnya</span>
            <span>Rp {{ number_format($property->total_others_income ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Total dan Rata-rata --}}
    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
        <div class="flex justify-between font-bold text-base">
            <span>Yearly Revenue</span>
            <span>Rp {{ number_format($property->dailyRevenue ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-semibold">
            <span>Average Room Rate</span>
            <span>Rp {{ number_format($property->averageRoomRate ?? 0, 0, ',', '.') }}</span>
        </div>
        
        {{-- ======================= AWAL PERUBAHAN ======================= --}}
        @php
            $averageRoomRate = $property->averageRoomRate ?? 0;
            $arrAfterTax = $averageRoomRate - ($averageRoomRate * 0.21);
        @endphp
        <div class="flex justify-between items-center font-semibold text-blue-600 dark:text-blue-400">
            <span class="pr-2">Net ARR (Setelah Pajak & Servis)</span>
            <span class="whitespace-nowrap flex-shrink-0">Rp {{ number_format($arrAfterTax, 0, ',', '.') }}</span>
        </div>
        {{-- ======================= AKHIR PERUBAHAN ======================= --}}
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.properties.show', $property->id) }}" class="block w-full text-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 font-semibold">
            Lihat Detail
        </a>
    </div>
</div>
