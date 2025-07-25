<x-property-user-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pendapatan Tanggal: ') }} {{ \Carbon\Carbon::parse($dailyIncome->date)->isoFormat('D MMMM YYYY') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- ======================= TAMBAHKAN BLOK INI ======================= --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                            <strong class="font-bold">Oops! Ada yang salah.</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- ================================================================= --}}
                    
                    <form method="POST" action="{{ route('property.income.update', ['dailyIncome' => $dailyIncome->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Tanggal -->
                        <div class="mb-6">
                            <x-input-label for="date" :value="__('Tanggal')" />
                            <x-text-input id="date" class="block mt-1 w-full md:w-1/2" type="date" name="date" :value="old('date', $dailyIncome->date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <!-- Income Categories -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div class="col-span-1 border-t pt-4"><h3 class="font-semibold text-lg">Kamar</h3></div>
                            <div class="col-span-1 border-t pt-4"><h3 class="font-semibold text-lg">Pendapatan (Rp)</h3></div>

                            <!-- Walk In Guest -->
                            <div>
                                <x-input-label for="offline_rooms" :value="__('Walk In Guest (Kamar)')" />
                                <x-text-input id="offline_rooms" class="block mt-1 w-full" type="number" name="offline_rooms" :value="old('offline_rooms', $dailyIncome->offline_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="offline_room_income" :value="__('Walk In Guest (Pendapatan)')" />
                                <x-text-input id="offline_room_income" class="block mt-1 w-full" type="number" name="offline_room_income" :value="old('offline_room_income', $dailyIncome->offline_room_income)" />
                            </div>

                            <!-- OTA -->
                            <div>
                                <x-input-label for="online_rooms" :value="__('OTA (Kamar)')" />
                                <x-text-input id="online_rooms" class="block mt-1 w-full" type="number" name="online_rooms" :value="old('online_rooms', $dailyIncome->online_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="online_room_income" :value="__('OTA (Pendapatan)')" />
                                <x-text-input id="online_room_income" class="block mt-1 w-full" type="number" name="online_room_income" :value="old('online_room_income', $dailyIncome->online_room_income)" />
                            </div>

                            <!-- TA/Travel Agent -->
                            <div>
                                <x-input-label for="ta_rooms" :value="__('TA/Travel Agent (Kamar)')" />
                                <x-text-input id="ta_rooms" class="block mt-1 w-full" type="number" name="ta_rooms" :value="old('ta_rooms', $dailyIncome->ta_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="ta_income" :value="__('TA/Travel Agent (Pendapatan)')" />
                                <x-text-input id="ta_income" class="block mt-1 w-full" type="number" name="ta_income" :value="old('ta_income', $dailyIncome->ta_income)" />
                            </div>
                            
                            <!-- Gov/Government -->
                            <div>
                                <x-input-label for="gov_rooms" :value="__('Gov/Government (Kamar)')" />
                                <x-text-input id="gov_rooms" class="block mt-1 w-full" type="number" name="gov_rooms" :value="old('gov_rooms', $dailyIncome->gov_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="gov_income" :value="__('Gov/Government (Pendapatan)')" />
                                <x-text-input id="gov_income" class="block mt-1 w-full" type="number" name="gov_income" :value="old('gov_income', $dailyIncome->gov_income)" />
                            </div>
                            
                            <!-- Corp/Corporation -->
                             <div>
                                <x-input-label for="corp_rooms" :value="__('Corp/Corporation (Kamar)')" />
                                <x-text-input id="corp_rooms" class="block mt-1 w-full" type="number" name="corp_rooms" :value="old('corp_rooms', $dailyIncome->corp_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="corp_income" :value="__('Corp/Corporation (Pendapatan)')" />
                                <x-text-input id="corp_income" class="block mt-1 w-full" type="number" name="corp_income" :value="old('corp_income', $dailyIncome->corp_income)" />
                            </div>

                            <!-- Compliment -->
                            <div>
                                <x-input-label for="compliment_rooms" :value="__('Compliment (Kamar)')" />
                                <x-text-input id="compliment_rooms" class="block mt-1 w-full" type="number" name="compliment_rooms" :value="old('compliment_rooms', $dailyIncome->compliment_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="compliment_income" :value="__('Compliment (Pendapatan)')" />
                                <x-text-input id="compliment_income" class="block mt-1 w-full" type="number" name="compliment_income" :value="old('compliment_income', $dailyIncome->compliment_income)" />
                            </div>

                            <!-- House Use -->
                            <div>
                                <x-input-label for="house_use_rooms" :value="__('House Use (Kamar)')" />
                                <x-text-input id="house_use_rooms" class="block mt-1 w-full" type="number" name="house_use_rooms" :value="old('house_use_rooms', $dailyIncome->house_use_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="house_use_income" :value="__('House Use (Pendapatan)')" />
                                <x-text-input id="house_use_income" class="block mt-1 w-full" type="number" name="house_use_income" :value="old('house_use_income', $dailyIncome->house_use_income)" />
                            </div>
                            <div>
                                <x-input-label for="afiliasi_rooms" :value="__('Afiliasi (Kamar)')" />
                                <x-text-input id="afiliasi_rooms" class="block mt-1 w-full" type="number" name="afiliasi_rooms" :value="old('afiliasi_rooms', $dailyIncome->afiliasi_rooms)" />
                            </div>
                            <div>
                                <x-input-label for="afiliasi_room_income" :value="__('Afiliasi (Pendapatan)')" />
                                <x-text-input id="afiliasi_room_income" class="block mt-1 w-full" type="number" name="afiliasi_room_income" :value="old('afiliasi_room_income', $dailyIncome->afiliasi_room_income)" />
                            </div>

                            <!-- Other Incomes (no rooms) -->
                            <div class="col-span-full border-t pt-4"></div>
                            
                            <div>
                                <x-input-label for="mice_income" :value="__('MICE (Pendapatan)')" />
                                <x-text-input id="mice_income" class="block mt-1 w-full" type="number" name="mice_income" :value="old('mice_income', $dailyIncome->mice_income)" />
                            </div>
                            
                            <div class="col-span-1">
                                <x-input-label for="breakfast_income" :value="__('Breakfast (Pendapatan)')" />
                                <x-text-input id="breakfast_income" class="block mt-1 w-full" type="number" name="breakfast_income" :value="old('breakfast_income', $dailyIncome->breakfast_income)" />
                            </div>

                            <div class="col-span-1 md:col-start-2">
                                <x-input-label for="lunch_income" :value="__('Lunch (Pendapatan)')" />
                                <x-text-input id="lunch_income" class="block mt-1 w-full" type="number" name="lunch_income" :value="old('lunch_income', $dailyIncome->lunch_income)" />
                            </div>

                            <div class="col-span-1 md:col-start-2">
                                <x-input-label for="dinner_income" :value="__('Dinner (Pendapatan)')" />
                                <x-text-input id="dinner_income" class="block mt-1 w-full" type="number" name="dinner_income" :value="old('dinner_income', $dailyIncome->dinner_income)" />
                            </div>
                            
                            <div>
                                <x-input-label for="others_income" :value="__('Lainnya (Pendapatan)')" />
                                <x-text-input id="others_income" class="block mt-1 w-full" type="number" name="others_income" :value="old('others_income', $dailyIncome->others_income)" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.history.back()">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button class="ml-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-property-user-layout>
