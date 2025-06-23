<div class="flex flex-col justify-between h-full">
    {{-- Bagian Atas: Judul dan Rincian Pendapatan --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $property->name }}</h3>
        
        <div class="mt-4">
            <table class="w-full text-sm">
                <tbody class="text-gray-600 dark:text-gray-400">
                    {{-- Loop ini menggunakan data yang sudah di-sum oleh controller untuk efisiensi --}}
                    @php
                        // Kalkulasi Total F&B untuk ditampilkan sebagai sub-judul
                        $totalFbIncome = ($property->total_breakfast_income ?? 0) + ($property->total_lunch_income ?? 0) + ($property->total_dinner_income ?? 0);
                    @endphp

                    {{-- Menampilkan semua pendapatan kamar --}}
                    @foreach (['offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent', 'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment', 'house_use_income' => 'House Use'] as $key => $label)
                        @if ($property->{'total_' . $key} > 0)
                        <tr>
                            <td class="py-1.5 pr-4">{{ $label }}</td>
                            <td class="py-1.5 text-right font-medium text-gray-700 dark:text-gray-300">
                                Rp {{ number_format($property->{'total_' . $key} ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    @endforeach

                    {{-- Sub-bagian F&B --}}
                    @if ($totalFbIncome > 0)
                    <tr class="border-t border-dashed border-gray-300 dark:border-gray-600">
                        <td class="pt-3 pb-1 pr-4 font-semibold text-gray-500 dark:text-gray-400" colspan="2">Pendapatan F&B</td>
                    </tr>
                    @foreach (['breakfast_income' => 'Breakfast', 'lunch_income' => 'Lunch', 'dinner_income' => 'Dinner'] as $key => $label)
                        @if ($property->{'total_' . $key} > 0)
                        <tr>
                            <td class="py-1.5 pr-4 pl-4">{{ $label }}</td>
                            <td class="py-1.5 text-right font-medium text-gray-700 dark:text-gray-300">
                                Rp {{ number_format($property->{'total_' . $key} ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @endif

                    {{-- Sub-bagian MICE --}}
                    @if(isset($property->mice_revenue_breakdown) && $property->mice_revenue_breakdown->isNotEmpty())
                        <tr class="border-t border-dashed border-gray-300 dark:border-gray-600">
                            <td class="pt-3 pb-1 pr-4 font-semibold text-gray-500 dark:text-gray-400" colspan="2">Pendapatan MICE (dari Sales)</td>
                        </tr>
                        @foreach($property->mice_revenue_breakdown as $mice)
                            <tr>
                                <td class="py-1.5 pr-4 pl-4">{{ $mice->miceCategory->name ?? 'Lainnya' }}</td>
                                <td class="py-1.5 text-right font-medium text-gray-700 dark:text-gray-300">
                                    Rp {{ number_format($mice->total_mice_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- Pendapatan Lainnya --}}
                    @if ($property->total_others_income > 0)
                        <tr class="border-t border-dashed border-gray-300 dark:border-gray-600">
                            <td class="pt-3 pb-1 pr-4 font-semibold text-gray-500 dark:text-gray-400">Lainnya</td>
                            <td class="pt-3 pb-1 text-right font-medium text-gray-700 dark:text-gray-300">
                                Rp {{ number_format($property->total_others_income ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
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
