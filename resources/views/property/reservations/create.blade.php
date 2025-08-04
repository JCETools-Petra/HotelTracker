<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Reservasi OTA Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>