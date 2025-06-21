<x-sales-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        {{ __('Tambah Booking Baru') }}
    </h2>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('sales.bookings.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div>
                        <label for="booking_date" class="block font-medium text-sm text-gray-700">Tanggal Booking</label>
                        <input type="date" name="booking_date" id="booking_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('booking_date', date('Y-m-d')) }}" required>
                        @error('booking_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="client_name" class="block font-medium text-sm text-gray-700">Nama Klien</label>
                        <input type="text" name="client_name" id="client_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('client_name') }}" required>
                        @error('client_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="mice_category_id" class="block font-medium text-sm text-gray-700">Kategori MICE</label>
                        <select name="mice_category_id" id="mice_category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($miceCategories as $category)
                                <option value="{{ $category->id }}" {{ old('mice_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mice_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="event_date" class="block font-medium text-sm text-gray-700">Tanggal Acara</label>
                        <input type="date" name="event_date" id="event_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('event_date') }}" required>
                         @error('event_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                     <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                           <label for="start_time" class="block font-medium text-sm text-gray-700">Jam Mulai</label>
                           <input type="time" name="start_time" id="start_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('start_time') }}" required>
                           @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                           <label for="end_time" class="block font-medium text-sm text-gray-700">Jam Selesai</label>
                           <input type="time" name="end_time" id="end_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('end_time') }}" required>
                           @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <div>
                        <label for="participants" class="block font-medium text-sm text-gray-700">Jumlah Peserta</label>
                        <input type="number" name="participants" id="participants" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('participants') }}" required>
                        @error('participants') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="property_id" class="block font-medium text-sm text-gray-700">Ruang yang Digunakan</label>
                        <select name="property_id" id="property_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="person_in_charge" class="block font-medium text-sm text-gray-700">Penanggung Jawab Acara</label>
                        <input type="text" name="person_in_charge" id="person_in_charge" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('person_in_charge') }}" required>
                        @error('person_in_charge') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                        <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                            <option value="Booking Sementara">Booking Sementara</option>
                            <option value="Booking Pasti">Booking Pasti</option>
                            <option value="Cancel">Cancel</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                     <div class="mt-4">
                        <label for="notes" class="block font-medium text-sm text-gray-700">Catatan Khusus</label>
                        <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('notes') }}</textarea>
                         @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('sales.bookings.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Simpan Booking
                </button>
            </div>
        </form>
    </div>
</x-sales-layout>