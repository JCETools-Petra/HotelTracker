{{-- Nama Ruangan --}}
<div>
    <x-input-label for="name" :value="__('Nama Ruangan')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

{{-- Kapasitas --}}
<div class="mt-4">
    <x-input-label for="capacity" :value="__('Kapasitas')" />
    <x-text-input id="capacity" class="block mt-1 w-full" type="text" name="capacity" :value="old('capacity', $room->capacity ?? '')" />
    <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
</div>

{{-- Catatan --}}
<div class="mt-4">
    <x-input-label for="notes" :value="__('Catatan')" />
    <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $room->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.properties.rooms.index', $property ?? $room->property) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline mr-4">
        Batal
    </a>
    <x-primary-button>
        {{ isset($room) ? 'Update Ruangan' : 'Simpan Ruangan' }}
    </x-primary-button>
</div>
