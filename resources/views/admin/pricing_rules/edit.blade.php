{{-- Menggunakan layout utama admin --}}
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atur Aturan Harga untuk Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h3 class="text-2xl font-bold">{{ $property->name }}</h3>
                    <p class="text-gray-600 mb-6">{{ $property->address }}</p>

                    {{-- Menampilkan pesan sukses --}}
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('admin.pricing-rules.update', $property->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="publish_rate" class="block font-medium text-sm text-gray-700">Publish Rate (Harga Normal)</label>
                                <input type="number" id="publish_rate" name="publish_rate" value="{{ old('publish_rate', $rule->publish_rate) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('publish_rate')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bottom_rate" class="block font-medium text-sm text-gray-700">Bottom Rate (Harga Paling Rendah)</label>
                                <input type="number" id="bottom_rate" name="bottom_rate" value="{{ old('bottom_rate', $rule->bottom_rate) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('bottom_rate')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tier_limit" class="block font-medium text-sm text-gray-700">Jumlah Kamar per Tingkat Harga</label>
                                <input type="number" id="tier_limit" name="tier_limit" value="{{ old('tier_limit', $rule->tier_limit) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('tier_limit')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="percentage_increase" class="block font-medium text-sm text-gray-700">Persentase Kenaikan per Tingkat (%)</label>
                                <input type="number" step="0.01" id="percentage_increase" name="percentage_increase" value="{{ old('percentage_increase', $rule->percentage_increase) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('percentage_increase')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Aturan Harga
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>