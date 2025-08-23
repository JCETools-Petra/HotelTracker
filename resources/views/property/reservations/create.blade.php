<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
<<<<<<< HEAD
            {{ __('Buat Reservasi OTA Baru') }}
=======
            {{ __('Tambah Reservasi Baru') }}
>>>>>>> origin/master
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
<<<<<<< HEAD
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200">

                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Properti: <span class="text-indigo-600 dark:text-indigo-400">{{ $property->name }}</span>
                        </h3>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('property.reservations.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="checkin_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tanggal Check-in</label>
                                    <input type="date" name="checkin_date" id="checkin_date" value="{{ old('checkin_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="mb-4">
                                    <label for="checkout_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tanggal Check-out</label>
                                    <input type="date" name="checkout_date" id="checkout_date" value="{{ old('checkout_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="source" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sumber OTA</label>
                                    <select name="source" id="source" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                        @foreach($sources as $source)
                                            <option value="{{ $source }}" {{ old('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="guest_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Tamu</label>
                                    <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                </div>
=======
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('property.reservations.store') }}" method="POST">
                        @csrf
                        {{-- Input tersembunyi untuk property_id --}}
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <x-input-label for="property_name" :value="__('Properti')" />
                                <x-text-input id="property_name" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text" value="{{ $property->name }}" readonly />
                            </div>

                            <div>
                                <x-input-label for="room_type_id" :value="__('Pilih Tipe Kamar')" />
                                <select name="room_type_id" id="room_type_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>-- Pilih Tipe Kamar --</option>
                                    @foreach($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                            {{ $roomType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('room_type_id')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="display_price" :value="__('Harga BAR Aktif')" />
                                <x-text-input id="display_price" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text" value="-" readonly />
                                <input type="hidden" name="final_price" id="final_price">
                            </div>

                            <div class="col-span-2"><hr class="dark:border-gray-600 mt-2"></div>
                            
                            <div class="col-span-2">
                                <x-input-label for="guest_name" :value="__('Nama Tamu')" />
                                <x-text-input id="guest_name" name="guest_name" :value="old('guest_name')" class="block mt-1 w-full" type="text" required />
                            </div>
                            
                            <div>
                                <x-input-label for="checkin_date" :value="__('Tanggal Check-in')" />
                                <x-text-input id="checkin_date" name="checkin_date" :value="old('checkin_date')" class="block mt-1 w-full" type="date" required />
                            </div>

                            <div>
                                <x-input-label for="checkout_date" :value="__('Tanggal Check-out')" />
                                <x-text-input id="checkout_date" name="checkout_date" :value="old('checkout_date')" class="block mt-1 w-full" type="date" required />
                            </div>

                             <div>
                                <x-input-label for="number_of_rooms" :value="__('Jumlah Kamar')" />
                                <x-text-input id="number_of_rooms" name="number_of_rooms" value="{{ old('number_of_rooms', 1) }}" class="block mt-1 w-full" type="number" required />
>>>>>>> origin/master
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
<<<<<<< HEAD
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Reservasi
                            </button>
=======
                            <a href="{{ route('property.reservations.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
>>>>>>> origin/master
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
=======
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roomTypeSelect = document.getElementById('room_type_id');
            const displayPrice = document.getElementById('display_price');
            const finalPrice = document.getElementById('final_price');
            
            const priceUrlTemplate = "{{ route('property.room-types.active-price', ['roomType' => 'ROOMTYPE_ID']) }}";

            roomTypeSelect.addEventListener('change', function () {
                const roomTypeId = this.value;
                displayPrice.value = 'Menghitung...';
                finalPrice.value = '';

                if (!roomTypeId) {
                    displayPrice.value = '-';
                    return;
                }
                
                const fetchUrl = priceUrlTemplate.replace('ROOMTYPE_ID', roomTypeId);
                
                fetch(fetchUrl)
                    .then(response => response.json())
                    .then(data => {
                        finalPrice.value = data.price;
                        displayPrice.value = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data.price);
                    });
            });
        });
    </script>
    @endpush
>>>>>>> origin/master
</x-app-layout>