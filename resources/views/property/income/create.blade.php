<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catat Pendapatan Harian untuk ') }} {{ $property->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('property.income.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="date" :value="__('Tanggal')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', \Carbon\Carbon::today()->toDateString())" required autofocus />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="walkin_guest_rooms" :value="__('Walk In Guest (Jumlah Kamar)')" />
                                <x-text-input id="walkin_guest_rooms" class="block mt-1 w-full" type="number" name="walkin_guest_rooms" :value="old('walkin_guest_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('walkin_guest_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="walkin_guest_income" :value="__('Walk In Guest (Pendapatan)')" />
                                <x-text-input id="walkin_guest_income" class="block mt-1 w-full" type="number" step="0.01" name="walkin_guest_income" :value="old('walkin_guest_income', 0)" required />
                                <x-input-error :messages="$errors->get('walkin_guest_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="ota_rooms" :value="__('OTA (Jumlah Kamar)')" />
                                <x-text-input id="ota_rooms" class="block mt-1 w-full" type="number" name="ota_rooms" :value="old('ota_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('ota_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="ota_income" :value="__('OTA (Pendapatan)')" />
                                <x-text-input id="ota_income" class="block mt-1 w-full" type="number" step="0.01" name="ota_income" :value="old('ota_income', 0)" required />
                                <x-input-error :messages="$errors->get('ota_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="ta_rooms" :value="__('TA / Travel Agent (Jumlah Kamar)')" />
                                <x-text-input id="ta_rooms" class="block mt-1 w-full" type="number" name="ta_rooms" :value="old('ta_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('ta_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="ta_income" :value="__('TA / Travel Agent (Pendapatan)')" />
                                <x-text-input id="ta_income" class="block mt-1 w-full" type="number" step="0.01" name="ta_income" :value="old('ta_income', 0)" required />
                                <x-input-error :messages="$errors->get('ta_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="gov_rooms" :value="__('Gov / Government (Jumlah Kamar)')" />
                                <x-text-input id="gov_rooms" class="block mt-1 w-full" type="number" name="gov_rooms" :value="old('gov_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('gov_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gov_income" :value="__('Gov / Government (Pendapatan)')" />
                                <x-text-input id="gov_income" class="block mt-1 w-full" type="number" step="0.01" name="gov_income" :value="old('gov_income', 0)" required />
                                <x-input-error :messages="$errors->get('gov_income')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="corp_rooms" :value="__('Corp / Corporation (Jumlah Kamar)')" />
                                <x-text-input id="corp_rooms" class="block mt-1 w-full" type="number" name="corp_rooms" :value="old('corp_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('corp_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="corp_income" :value="__('Corp / Corporation (Pendapatan)')" />
                                <x-text-input id="corp_income" class="block mt-1 w-full" type="number" step="0.01" name="corp_income" :value="old('corp_income', 0)" required />
                                <x-input-error :messages="$errors->get('corp_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="compliment_rooms" :value="__('Compliment (Jumlah Kamar)')" />
                                <x-text-input id="compliment_rooms" class="block mt-1 w-full" type="number" name="compliment_rooms" :value="old('compliment_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('compliment_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="compliment_income" :value="__('Compliment (Pendapatan)')" />
                                <x-text-input id="compliment_income" class="block mt-1 w-full" type="number" step="0.01" name="compliment_income" :value="old('compliment_income', 0)" required />
                                <x-input-error :messages="$errors->get('compliment_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="house_use_rooms" :value="__('House Use (Jumlah Kamar)')" />
                                <x-text-input id="house_use_rooms" class="block mt-1 w-full" type="number" name="house_use_rooms" :value="old('house_use_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('house_use_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="house_use_income" :value="__('House Use (Pendapatan)')" />
                                <x-text-input id="house_use_income" class="block mt-1 w-full" type="number" step="0.01" name="house_use_income" :value="old('house_use_income', 0)" required />
                                <x-input-error :messages="$errors->get('house_use_income')" class="mt-2" />
                            </div>
                        </div>
                        
                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <div class="mt-4">
                            <x-input-label for="mice_income" :value="__('Pendapatan MICE')" />
                            <x-text-input id="mice_income" class="block mt-1 w-full" type="number" step="0.01" name="mice_income" :value="old('mice_income', 0)" required />
                            <x-input-error :messages="$errors->get('mice_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fnb_income" :value="__('Pendapatan F&B')" />
                            <x-text-input id="fnb_income" class="block mt-1 w-full" type="number" step="0.01" name="fnb_income" :value="old('fnb_income', 0)" required />
                            <x-input-error :messages="$errors->get('fnb_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="others_income" :value="__('Pendapatan Lainnya')" />
                            <x-text-input id="others_income" class="block mt-1 w-full" type="number" step="0.01" name="others_income" :value="old('others_income', 0)" required />
                            <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>