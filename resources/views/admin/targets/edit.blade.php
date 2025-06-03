<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Target Pendapatan untuk Properti: ') }} <span class="font-bold">{{ $target->property->name }}</span>
            </h2>
            <nav>
                <x-nav-link :href="route('admin.targets.index')" :active="request()->routeIs('admin.targets.index')">
                    {{ __('Kembali ke Daftar Target') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.targets.update', $target->id) }}">
                        @csrf
                        @method('PUT') {{-- Metode HTTP untuk update --}}

                        <div class="mt-4">
                            <x-input-label for="property_name" :value="__('Properti')" />
                            <x-text-input id="property_name" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text" 
                                          :value="$target->property->name" readonly disabled />
                            {{-- Kita tidak mengizinkan perubahan property_id di form edit ini untuk menjaga integritas unik --}}
                            {{-- Jika perlu diubah, admin harus menghapus dan membuat target baru untuk properti lain --}}
                        </div>

                        <div class="mt-4">
                            <x-input-label for="year" :value="__('Tahun Target')" />
                            <select id="year" name="year" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($years as $year_option)
                                    <option value="{{ $year_option }}" {{ old('year', $target->year) == $year_option ? 'selected' : '' }}>
                                        {{ $year_option }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="month" :value="__('Bulan Target')" />
                            <select id="month" name="month" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Bulan --</option>
                                @foreach ($months as $monthNumber => $monthName)
                                    <option value="{{ $monthNumber }}" {{ old('month', $target->month) == $monthNumber ? 'selected' : '' }}>
                                        {{ $monthName }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('month')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="target_amount" :value="__('Jumlah Target Pendapatan (Rp)')" />
                            <x-text-input id="target_amount" class="block mt-1 w-full" type="number" name="target_amount" :value="old('target_amount', $target->target_amount)" required step="1000" min="0"/>
                            <x-input-error :messages="$errors->get('target_amount')" class="mt-2" />
                        </div>
                        
                        {{-- Menampilkan pesan error custom jika ada untuk kombinasi unik --}}
                        @if ($errors->has('unique_target'))
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $errors->first('unique_target') }}</p>
                        @endif


                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('admin.targets.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
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
</x-app-layout>