<div class="flex flex-col justify-between h-full">
    {{-- Bagian Atas: Judul dan Rincian Pendapatan --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $property->name }}</h3>
        
        <div class="mt-4">
            <table class="w-full text-sm">
                <tbody class="text-gray-600 dark:text-gray-400">
                    {{-- Loop ini menggunakan data yang sudah di-sum oleh controller untuk efisiensi --}}
                    @foreach ($incomeCategories as $key => $label)
                    <tr>
                        <td class="py-1.5 pr-4">{{ $label }}</td>
                        <td class="py-1.5 text-right font-medium text-gray-700 dark:text-gray-300">
                            @php
                                // Mengambil data dari properti seperti $property->total_offline_room_income, dll.
                                $totalKey = 'total_' . $key;
                            @endphp
                            Rp {{ number_format($property->$totalKey ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="text-gray-800 dark:text-gray-200">
                    {{-- Menampilkan data yang sudah dihitung di controller --}}
                    <tr class="border-t-2 border-gray-300 dark:border-gray-700">
                        <td class="pt-3 pr-4 font-semibold">Daily Revenue</td>
                        <td class="pt-3 text-right text-base font-bold">
                            Rp {{ number_format($property->dailyRevenue ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-1.5 pr-4 font-semibold">Average Room Rate</td>
                        <td class="pt-1.5 text-right text-base font-bold">
                            Rp {{ number_format($property->averageRoomRate ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Bagian Bawah: Tombol Aksi --}}
    <div class="mt-5">
        <a href="{{ route('admin.properties.show', $property->id) }}" 
           class="inline-block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 text-sm font-semibold">
            Lihat Detail
        </a>
    </div>
</div>
