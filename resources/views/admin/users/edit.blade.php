<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Pengguna: ') }} <span class="font-bold">{{ $user->name }}</span>
            </h2>
            <nav>
                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                    {{ __('Kembali ke Daftar Pengguna') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT') {{-- Metode HTTP untuk update --}}

                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Alamat Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Hanya tampilkan pilihan properti jika pengguna adalah pengguna_properti --}}
                        @if ($user->role === 'pengguna_properti' || old('role') === 'pengguna_properti') {{-- Tambahkan old('role') jika peran bisa diubah --}}
                        <div class="mt-4">
                            <x-input-label for="property_id" :value="__('Properti yang Dikelola')" />
                            <select id="property_id" name="property_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Tidak Terkait Properti (Misal: Admin) --</option>
                                @foreach ($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id', $user->property_id) == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya relevan untuk peran Pengguna Properti. Pilih properti yang akan dikelola.</p>
                        </div>
                        @else
                            {{-- Jika admin, property_id biasanya null. Tampilkan info atau sembunyikan field. --}}
                            <input type="hidden" name="property_id" value="">
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Pengguna dengan peran '{{$user->role}}' tidak dikaitkan dengan properti spesifik.</p>
                        @endif
                        
                        {{-- Peran (Role) - Untuk saat ini kita tidak izinkan mengubah peran via form ini untuk kesederhanaan --}}
                        {{-- Jika ingin bisa mengubah peran, tambahkan dropdown peran di sini dan logika validasi di controller --}}
                        <div class="mt-4">
                             <x-input-label for="role_display" :value="__('Peran Saat Ini')" />
                             <x-text-input id="role_display" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text" :value="Str::title(str_replace('_', ' ', $user->role))" readonly disabled />
                        </div>


                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Kosongkan field password jika tidak ingin mengubah password.</p>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
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