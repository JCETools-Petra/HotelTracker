<<<<<<< HEAD
{{-- resources/views/admin/properties/_property_card.blade.php --}}
{{-- Variabel yang diharapkan: $property --}}
<div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
    <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $property->name }}</h4>
    <p class="text-sm text-gray-600 dark:text-gray-400">Total Catatan Pendapatan: {{ $property->total_income_records ?? 'N/A' }}</p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total MICE: Rp {{ number_format($property->total_mice_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total F&B: Rp {{ number_format($property->total_fnb_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Kamar Offline: Rp {{ number_format($property->total_offline_room_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Kamar Online: Rp {{ number_format($property->total_online_room_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Lainnya: Rp {{ number_format($property->total_others_income ?? 0, 0, ',', '.') }}
    </p>
    @php
        $grandTotal = ($property->total_mice_income ?? 0) +
                      ($property->total_fnb_income ?? 0) +
                      ($property->total_offline_room_income ?? 0) +
                      ($property->total_online_room_income ?? 0) +
                      ($property->total_others_income ?? 0);
    @endphp
    <p class="text-md font-semibold text-gray-800 dark:text-gray-200 mt-1">
        Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
    </p>
    <a href="{{ route('admin.properties.show', $property) }}"
       class="inline-block mt-3 text-sm px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-200">
        Lihat Detail & Grafik
    </a>
=======
{{-- resources/views/admin/properties/_property_card.blade.php --}}
{{-- Variabel yang diharapkan: $property --}}
<div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
    <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $property->name }}</h4>
    <p class="text-sm text-gray-600 dark:text-gray-400">Total Catatan Pendapatan: {{ $property->total_income_records ?? 'N/A' }}</p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total MICE: Rp {{ number_format($property->total_mice_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total F&B: Rp {{ number_format($property->total_fnb_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Kamar Offline: Rp {{ number_format($property->total_offline_room_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Kamar Online: Rp {{ number_format($property->total_online_room_income ?? 0, 0, ',', '.') }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Total Lainnya: Rp {{ number_format($property->total_others_income ?? 0, 0, ',', '.') }}
    </p>
    @php
        $grandTotal = ($property->total_mice_income ?? 0) +
                      ($property->total_fnb_income ?? 0) +
                      ($property->total_offline_room_income ?? 0) +
                      ($property->total_online_room_income ?? 0) +
                      ($property->total_others_income ?? 0);
    @endphp
    <p class="text-md font-semibold text-gray-800 dark:text-gray-200 mt-1">
        Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
    </p>
    <a href="{{ route('admin.properties.show', $property) }}"
       class="inline-block mt-3 text-sm px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-200">
        Lihat Detail & Grafik
    </a>
>>>>>>> d022ff7944e4652039483fc40f98e16fe7417648
</div>