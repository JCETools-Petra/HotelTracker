<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Tamu</label>
        <input type="text" name="guest_name" value="{{ old('guest_name', isset($reservation) ? $reservation->guest_name : '') }}" class="mt-1 block w-full border-gray-300 rounded" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Tamu</label>
        <input type="email" name="guest_email" value="{{ old('guest_email', isset($reservation) ? $reservation->guest_email : '') }}" class="mt-1 block w-full border-gray-300 rounded">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Check-in</label>
        <input type="date" name="checkin_date" value="{{ old('checkin_date', isset($reservation) ? $reservation->checkin_date : '') }}" class="mt-1 block w-full border-gray-300 rounded" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Check-out</label>
        <input type="date" name="checkout_date" value="{{ old('checkout_date', isset($reservation) ? $reservation->checkout_date : '') }}" class="mt-1 block w-full border-gray-300 rounded" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Kamar</label>
        <input type="number" min="1" name="number_of_rooms" value="{{ old('number_of_rooms', isset($reservation) ? $reservation->number_of_rooms : 1) }}" class="mt-1 block w-full border-gray-300 rounded" required>
    </div>
</div>
