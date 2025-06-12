<<<<<<< HEAD
{{-- resources/views/property/income/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pendapatan Harian untuk ') }} {{ $property->name }}
            {{ __(' tanggal ') }} {{ \Carbon\Carbon::parse($dailyIncome->date)->isoFormat('D MMMM YYYY') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('property.income.update', $dailyIncome->id) }}">
                        @csrf
                        @method('PUT') {{-- Penting untuk update --}}

                        <div>
                            <x-input-label for="date" :value="__('Tanggal')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $dailyIncome->date->format('Y-m-d'))" required autofocus />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="mice_income" :value="__('Pendapatan MICE')" />
                            <x-text-input id="mice_income" class="block mt-1 w-full" type="number" step="0.01" name="mice_income" :value="old('mice_income', $dailyIncome->mice_income)" required />
                            <x-input-error :messages="$errors->get('mice_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fnb_income" :value="__('Pendapatan F&B')" />
                            <x-text-input id="fnb_income" class="block mt-1 w-full" type="number" step="0.01" name="fnb_income" :value="old('fnb_income', $dailyIncome->fnb_income)" required />
                            <x-input-error :messages="$errors->get('fnb_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="offline_room_income" :value="__('Pendapatan Kamar (Offline)')" />
                            <x-text-input id="offline_room_income" class="block mt-1 w-full" type="number" step="0.01" name="offline_room_income" :value="old('offline_room_income', $dailyIncome->offline_room_income)" required />
                            <x-input-error :messages="$errors->get('offline_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="online_room_income" :value="__('Pendapatan Kamar (Online)')" />
                            <x-text-input id="online_room_income" class="block mt-1 w-full" type="number" step="0.01" name="online_room_income" :value="old('online_room_income', $dailyIncome->online_room_income)" required />
                            <x-input-error :messages="$errors->get('online_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="others_income" :value="__('Pendapatan Lainnya')" />
                            <x-text-input id="others_income" class="block mt-1 w-full" type="number" step="0.01" name="others_income" :value="old('others_income', $dailyIncome->others_income)" required />
                            <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('property.income.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
=======
{{-- resources/views/property/income/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pendapatan Harian untuk ') }} {{ $property->name }}
            {{ __(' tanggal ') }} {{ \Carbon\Carbon::parse($dailyIncome->date)->isoFormat('D MMMM YYYY') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('property.income.update', $dailyIncome->id) }}">
                        @csrf
                        @method('PUT') {{-- Penting untuk update --}}

                        <div>
                            <x-input-label for="date" :value="__('Tanggal')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $dailyIncome->date->format('Y-m-d'))" required autofocus />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="mice_income" :value="__('Pendapatan MICE')" />
                            <x-text-input id="mice_income" class="block mt-1 w-full" type="number" step="0.01" name="mice_income" :value="old('mice_income', $dailyIncome->mice_income)" required />
                            <x-input-error :messages="$errors->get('mice_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fnb_income" :value="__('Pendapatan F&B')" />
                            <x-text-input id="fnb_income" class="block mt-1 w-full" type="number" step="0.01" name="fnb_income" :value="old('fnb_income', $dailyIncome->fnb_income)" required />
                            <x-input-error :messages="$errors->get('fnb_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="offline_room_income" :value="__('Pendapatan Kamar (Offline)')" />
                            <x-text-input id="offline_room_income" class="block mt-1 w-full" type="number" step="0.01" name="offline_room_income" :value="old('offline_room_income', $dailyIncome->offline_room_income)" required />
                            <x-input-error :messages="$errors->get('offline_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="online_room_income" :value="__('Pendapatan Kamar (Online)')" />
                            <x-text-input id="online_room_income" class="block mt-1 w-full" type="number" step="0.01" name="online_room_income" :value="old('online_room_income', $dailyIncome->online_room_income)" required />
                            <x-input-error :messages="$errors->get('online_room_income')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="others_income" :value="__('Pendapatan Lainnya')" />
                            <x-text-input id="others_income" class="block mt-1 w-full" type="number" step="0.01" name="others_income" :value="old('others_income', $dailyIncome->others_income)" required />
                            <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('property.income.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
>>>>>>> d022ff7944e4652039483fc40f98e16fe7417648
</x-app-layout>