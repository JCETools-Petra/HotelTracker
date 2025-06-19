<x-admin-layout>
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
                        @method('PUT')

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

                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Peran (Role)')" />
                            <select name="role" id="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required onchange="togglePropertySelect(this.value)">
                                @foreach($roles as $roleValue => $roleLabel)
                                    <option value="{{ $roleValue }}" {{ old('role', $user->role) == $roleValue ? 'selected' : '' }}>
                                        {{ $roleLabel }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="mt-4" id="property-select-container" style="display: none;">
                            <x-input-label for="property_id" :value="__('Properti yang Dikelola')" />
                            <select name="property_id" id="property_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Tidak Terkait Properti --</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id', $user->property_id) == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Kosongkan field password jika tidak ingin mengubahnya.</p>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md ... mr-4">
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
    
    @push('scripts')
    <script>
        function togglePropertySelect(role) {
            const propertySelectContainer = document.getElementById('property-select-container');
            const propertySelectInput = document.getElementById('property_id');
            if (role === 'pengguna_properti') {
                propertySelectContainer.style.display = 'block';
                propertySelectInput.required = true;
            } else {
                propertySelectContainer.style.display = 'none';
                propertySelectInput.required = false;
                propertySelectInput.value = ''; // Kosongkan pilihan properti
            }
        }
        // Panggil saat halaman pertama kali dimuat untuk set state awal
        document.addEventListener('DOMContentLoaded', function() {
            togglePropertySelect(document.getElementById('role').value);
        });
    </script>
    @endpush
</x-admin-layout>