<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atur Amenities untuk Kamar: ') }} <span class="font-bold">{{ $room->room_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('housekeeping.inventory.update', $room->id) }}">
                        @csrf
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Item</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item di Keluar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($inventories as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="number"
                                                name="amenities[{{ $item->id }}][quantity]"
                                                class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                min="0"
                                                {{-- Mengisi nilai 0 secara default, tapi mempertahankan nilai lama jika ada kesalahan validasi --}}
                                                value="{{ old('amenities.'.$item->id.'.quantity', 0) }}"
                                            >
                                            @error('amenities.'.$item->id.'.quantity')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada item inventaris yang tersedia untuk kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('housekeeping.inventory.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
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
</x-app-layout>