<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">

                            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Branding</h3>
                                <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
                                    <div class="sm:col-span-6">
                                        <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Aplikasi</label>
                                        <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? '' }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div class="sm:col-span-6">
                                        <label for="app_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Aplikasi</label>
                                        <input type="file" name="app_logo" id="app_logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @if(isset($settings['logo_path']) && $settings['logo_path'])
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Current Logo" class="h-16 w-auto">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Input untuk ukuran logo LOGIN --}}
                                    <div class="sm:col-span-3">
                                        <label for="logo_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ukuran Logo Login (px)</label>
                                        <input type="number" name="logo_size" id="logo_size" value="{{ $settings['logo_size'] ?? 80 }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 80">
                                        <p class="mt-1 text-xs text-gray-500">Tinggi logo dalam piksel di halaman login.</p>
                                    </div>

                                    {{-- ======================= AWAL BLOK YANG DITAMBAHKAN ======================= --}}
                                    <div class="sm:col-span-3">
                                        <label for="sidebar_logo_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ukuran Logo Sidebar (px)</label>
                                        <input type="number" name="sidebar_logo_size" id="sidebar_logo_size" value="{{ $settings['sidebar_logo_size'] ?? 40 }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 40">
                                        <p class="mt-1 text-xs text-gray-500">Tinggi logo dalam piksel di sidebar.</p>
                                    </div>
                                    {{-- ======================= AKHIR BLOK YANG DITAMBAHKAN ======================= --}}

                                </div>
                            </div>
                            
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Pengaturan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>