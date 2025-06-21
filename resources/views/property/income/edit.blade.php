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
                                <x-input-error :messages="$errors->get('offline_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="offline_room_income" :value="__('Walk In Guest (Pendapatan)')" />
                                <x-text-input id="offline_room_income" class="block mt-1 w-full" type="number" name="offline_room_income" :value="old('offline_room_income', $dailyIncome->offline_room_income)" />
                                <x-input-error :messages="$errors->get('offline_room_income')" class="mt-2" />
                            </div>

                            <!-- OTA -->
                            <div>
                                <x-input-label for="online_rooms" :value="__('OTA (Kamar)')" />
                                <x-text-input id="online_rooms" class="block mt-1 w-full" type="number" name="online_rooms" :value="old('online_rooms', $dailyIncome->online_rooms)" />
                                <x-input-error :messages="$errors->get('online_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="online_room_income" :value="__('OTA (Pendapatan)')" />
                                <x-text-input id="online_room_income" class="block mt-1 w-full" type="number" name="online_room_income" :value="old('online_room_income', $dailyIncome->online_room_income)" />
                                <x-input-error :messages="$errors->get('online_room_income')" class="mt-2" />
                            </div>

                            <!-- TA/Travel Agent -->
                            <div>
                                <x-input-label for="ta_rooms" :value="__('TA/Travel Agent (Kamar)')" />
                                <x-text-input id="ta_rooms" class="block mt-1 w-full" type="number" name="ta_rooms" :value="old('ta_rooms', $dailyIncome->ta_rooms)" />
                                <x-input-error :messages="$errors->get('ta_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="ta_income" :value="__('TA/Travel Agent (Pendapatan)')" />
                                <x-text-input id="ta_income" class="block mt-1 w-full" type="number" name="ta_income" :value="old('ta_income', $dailyIncome->ta_income)" />
                                <x-input-error :messages="$errors->get('ta_income')" class="mt-2" />
                            </div>
                            
                            <!-- Gov/Government -->
                            <div>
                                <x-input-label for="gov_rooms" :value="__('Gov/Government (Kamar)')" />
                                <x-text-input id="gov_rooms" class="block mt-1 w-full" type="number" name="gov_rooms" :value="old('gov_rooms', $dailyIncome->gov_rooms)" />
                                <x-input-error :messages="$errors->get('gov_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gov_income" :value="__('Gov/Government (Pendapatan)')" />
                                <x-text-input id="gov_income" class="block mt-1 w-full" type="number" name="gov_income" :value="old('gov_income', $dailyIncome->gov_income)" />
                                <x-input-error :messages="$errors->get('gov_income')" class="mt-2" />
                            </div>
                            
                            <!-- Corp/Corporation -->
                             <div>
                                <x-input-label for="corp_rooms" :value="__('Corp/Corporation (Kamar)')" />
                                <x-text-input id="corp_rooms" class="block mt-1 w-full" type="number" name="corp_rooms" :value="old('corp_rooms', $dailyIncome->corp_rooms)" />
                                <x-input-error :messages="$errors->get('corp_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="corp_income" :value="__('Corp/Corporation (Pendapatan)')" />
                                <x-text-input id="corp_income" class="block mt-1 w-full" type="number" name="corp_income" :value="old('corp_income', $dailyIncome->corp_income)" />
                                <x-input-error :messages="$errors->get('corp_income')" class="mt-2" />
                            </div>

                            <!-- Compliment -->
                            <div>
                                <x-input-label for="compliment_rooms" :value="__('Compliment (Kamar)')" />
                                <x-text-input id="compliment_rooms" class="block mt-1 w-full" type="number" name="compliment_rooms" :value="old('compliment_rooms', $dailyIncome->compliment_rooms)" />
                                <x-input-error :messages="$errors->get('compliment_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="compliment_income" :value="__('Compliment (Pendapatan)')" />
                                <x-text-input id="compliment_income" class="block mt-1 w-full" type="number" name="compliment_income" :value="old('compliment_income', $dailyIncome->compliment_income)" />
                                <x-input-error :messages="$errors->get('compliment_income')" class="mt-2" />
                            </div>

                            <!-- House Use -->
                            <div>
                                <x-input-label for="house_use_rooms" :value="__('House Use (Kamar)')" />
                                <x-text-input id="house_use_rooms" class="block mt-1 w-full" type="number" name="house_use_rooms" :value="old('house_use_rooms', $dailyIncome->house_use_rooms)" />
                                <x-input-error :messages="$errors->get('house_use_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="house_use_income" :value="__('House Use (Pendapatan)')" />
                                <x-text-input id="house_use_income" class="block mt-1 w-full" type="number" name="house_use_income" :value="old('house_use_income', $dailyIncome->house_use_income)" />
                                <x-input-error :messages="$errors->get('house_use_income')" class="mt-2" />
                            </div>
                            
                            <!-- Other Incomes (no rooms) -->
                            <div class="col-span-full border-t pt-4"></div>
                            
                            <div>
                                <x-input-label for="mice_income" :value="__('MICE (Pendapatan)')" />
                                <x-text-input id="mice_income" class="block mt-1 w-full" type="number" name="mice_income" :value="old('mice_income', $dailyIncome->mice_income)" />
                                <x-input-error :messages="$errors->get('mice_income')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="fnb_income" :value="__('F&B (Pendapatan)')" />
                                <x-text-input id="fnb_income" class="block mt-1 w-full" type="number" name="fnb_income" :value="old('fnb_income', $dailyIncome->fnb_income)" />
                                <x-input-error :messages="$errors->get('fnb_income')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="others_income" :value="__('Lainnya (Pendapatan)')" />
                                <x-text-input id="others_income" class="block mt-1 w-full" type="number" name="others_income" :value="old('others_income', $dailyIncome->others_income)" />
                                <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
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
