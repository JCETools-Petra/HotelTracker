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
                        <h3 class="font-semibold text-lg">Pendapatan Kamar</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="offline_rooms" :value="__('Offline / Walk-in (Jumlah Kamar)')" />
                                <x-text-input id="offline_rooms" class="block mt-1 w-full" type="number" name="offline_rooms" :value="old('offline_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('offline_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="offline_room_income" :value="__('Offline / Walk-in (Pendapatan)')" />
                                <x-text-input id="offline_room_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="offline_room_income" :value="old('offline_room_income', 0)" required />
                                <x-input-error :messages="$errors->get('offline_room_income')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="online_rooms" :value="__('Online / OTA (Jumlah Kamar)')" />
                                <x-text-input id="online_rooms" class="block mt-1 w-full" type="number" name="online_rooms" :value="old('online_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('online_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="online_room_income" :value="__('Online / OTA (Pendapatan)')" />
                                <x-text-input id="online_room_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="online_room_income" :value="old('online_room_income', 0)" required />
                                <x-input-error :messages="$errors->get('online_room_income')" class="mt-2" />
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
                                <x-text-input id="ta_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="ta_income" :value="old('ta_income', 0)" required />
                                <x-input-error :messages="$errors->get('ta_income')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="gov_rooms" :value="__('Government (Jumlah Kamar)')" />
                                <x-text-input id="gov_rooms" class="block mt-1 w-full" type="number" name="gov_rooms" :value="old('gov_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('gov_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gov_income" :value="__('Government (Pendapatan)')" />
                                <x-text-input id="gov_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="gov_income" :value="old('gov_income', 0)" required />
                                <x-input-error :messages="$errors->get('gov_income')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="corp_rooms" :value="__('Corporation (Jumlah Kamar)')" />
                                <x-text-input id="corp_rooms" class="block mt-1 w-full" type="number" name="corp_rooms" :value="old('corp_rooms', 0)" required />
                                <x-input-error :messages="$errors->get('corp_rooms')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="corp_income" :value="__('Corporation (Pendapatan)')" />
                                <x-text-input id="corp_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="corp_income" :value="old('corp_income', 0)" required />
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
                                <x-text-input id="compliment_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="compliment_income" :value="old('compliment_income', 0)" required />
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
                                <x-text-input id="house_use_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="house_use_income" :value="old('house_use_income', 0)" required />
                                <x-input-error :messages="$errors->get('house_use_income')" class="mt-2" />
                            </div>
                        </div>
                        
                        <hr class="my-6 border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-lg">Pendapatan Lainnya</h3>

                        <div class="mt-4">
                            <x-input-label for="mice_income" :value="__('Pendapatan MICE')" />
                            <x-text-input id="mice_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="mice_income" :value="old('mice_income', 0)" required />
                            <x-input-error :messages="$errors->get('mice_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fnb_income" :value="__('Pendapatan F&B')" />
                            <x-text-input id="fnb_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="fnb_income" :value="old('fnb_income', 0)" required />
                            <x-input-error :messages="$errors->get('fnb_income')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="others_income" :value="__('Pendapatan Lainnya')" />
                            <x-text-input id="others_income" class="income-input block mt-1 w-full" type="number" step="0.01" name="others_income" :value="old('others_income', 0)" required />
                            <x-input-error :messages="$errors->get('others_income')" class="mt-2" />
                        </div>
                        
                        {{-- Elemen untuk menampilkan total pendapatan --}}
                        <hr class="my-6 border-gray-200 dark:border-gray-700">
                        <div class="mt-4">
                            <x-input-label for="total_income_display" :value="__('Total Pendapatan (Otomatis)')" />
                            <x-text-input id="total_income_display" class="block mt-1 w-full bg-gray-200 dark:bg-gray-700 font-bold" type="text" readonly />
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Daftar semua ID dari input pendapatan
            const incomeInputIds = [
                'offline_room_income', 'online_room_income', 'ta_income', 'gov_income',
                'corp_income', 'compliment_income', 'house_use_income', 'mice_income',
                'fnb_income', 'others_income'
            ];

            const totalDisplayElement = document.getElementById('total_income_display');

            function calculateTotal() {
                let totalIncome = 0;
                incomeInputIds.forEach(id => {
                    const inputElement = document.getElementById(id);
                    if (inputElement && inputElement.value) {
                        totalIncome += parseFloat(inputElement.value) || 0;
                    }
                });

                if(totalDisplayElement) {
                    // Format sebagai Rupiah
                    totalDisplayElement.value = 'Rp ' + totalIncome.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }

            // Tambahkan event listener ke setiap input pendapatan
            incomeInputIds.forEach(id => {
                const inputElement = document.getElementById(id);
                if (inputElement) {
                    inputElement.addEventListener('input', calculateTotal);
                }
            });

            // Hitung total saat halaman pertama kali dimuat
            calculateTotal();
        });
    </script>
</x-app-layout>