<x-sales-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Booking: ') }} {{ $booking->booking_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form action="{{ route('sales.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div>
                                <label for="booking_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tanggal Booking</label>
                                <input type="date" name="booking_date" id="booking_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('booking_date', $booking->booking_date) }}" required>
                                 @error('booking_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="client_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Klien</label>
                                <input type="text" name="client_name" id="client_name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('client_name', $booking->client_name) }}" required>
                                @error('client_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="mice_category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori MICE</label>
                                <select name="mice_category_id" id="mice_category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($miceCategories as $category)
                                        <option value="{{ $category->id }}" @selected(old('mice_category_id', $booking->mice_category_id) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mice_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="event_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tanggal Acara</label>
                                <input type="date" name="event_date" id="event_date" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('event_date', $booking->event_date) }}" required>
                                @error('event_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                   <label for="start_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Jam Mulai</label>
                                   <input type="time" name="start_time" id="start_time" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('start_time', $booking->start_time) }}" required>
                                   @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                   <label for="end_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Jam Selesai</label>
                                   <input type="time" name="end_time" id="end_time" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('end_time', $booking->end_time) }}" required>
                                   @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="down_payment" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Uang Muka / DP (Rp)</label>
                                <input type="number" name="down_payment" id="down_payment" step="0.01" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('down_payment', $booking->down_payment) }}">
                                @error('down_payment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <div>
                                <label for="participants" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Jumlah Peserta</label>
                                <input type="number" name="participants" id="participants" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('participants', $booking->participants) }}" required>
                                @error('participants') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="property_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ruang yang Digunakan</label>
                                <select name="property_id" id="property_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}" @selected(old('property_id', $booking->property_id) == $property->id)>
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>
                                 @error('property_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="person_in_charge" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Penanggung Jawab Acara</label>
                                <input type="text" name="person_in_charge" id="person_in_charge" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ old('person_in_charge', $booking->person_in_charge) }}" required>
                                @error('person_in_charge') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                    <option value="Booking Sementara" @selected(old('status', $booking->status) == 'Booking Sementara')>Booking Sementara</option>
                                    <option value="Booking Pasti" @selected(old('status', $booking->status) == 'Booking Pasti')>Booking Pasti</option>
                                    <option value="Cancel" @selected(old('status', $booking->status) == 'Cancel')>Cancel</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div class="mt-4">
                                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Catatan Khusus</label>
                                <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('notes', $booking->notes) }}</textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-4">
                                <label for="total_price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Total Harga (Otomatis dari BEO)</label>
                                <input type="text" id="total_price" value="Rp {{ number_format($booking->total_price, 0, ',', '.') }}" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('sales.bookings.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sales-layout>