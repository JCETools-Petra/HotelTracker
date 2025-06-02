<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Pendapatan Harian untuk ') }} {{ $property->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('property.income.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="date" :value="__('Tanggal')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="$date" required autofocus />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

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
                            <x-input-label for="offline_room_income" :value="__('Pendapatan Kamar (Offline)')" />
                            <x-text-input id="offline_room_income" class="block mt-1 w-full" type="number" step="0.01" name="offline_room_income" :value="old('offline_room_income', 0)" required />
                            <x-input-error :messages="$errors->get('offline_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="online_room_income" :value="__('Pendapatan Kamar (Online)')" />
                            <x-text-input id="online_room_income" class="block mt-1 w-full" type="number" step="0.01" name="online_room_income" :value="old('online_room_income', 0)" required />
                            <x-input-error :messages="$errors->get('online_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="others_income" :value="__('Pendapatan Lainnya')" />
                            <x-text-input id="others_income" class="block mt-1 w-full" type="number" step="0.01" name="others_income" :value="old('others_income', 0)" required />
                            <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>