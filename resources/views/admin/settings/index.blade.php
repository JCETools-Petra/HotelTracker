<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h3 class="text-lg font-semibold border-b dark:border-gray-700 pb-2 mb-4">Branding</h3>

                        {{-- Input Logo --}}
                        <div class="mb-6">
                            <label for="logo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Logo Aplikasi (PNG, JPG, SVG)</label>
                            <input type="file" name="logo" id="logo" class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            
                            @if($settings['logo_path'] ?? null)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Logo Saat Ini:</p>
                                <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo Saat Ini" class="mt-2 h-12 bg-gray-200 p-1 rounded">
                            </div>
                            @endif
                        </div>

                        {{-- Input Ukuran Logo --}}
                        <div class="mb-6">
                            <label for="logo_size" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Lebar Logo di Sidebar (dalam pixel)</label>
                             <input type="number" name="logo_size" id="logo_size" value="{{ old('logo_size', $settings['logo_size'] ?? 60) }}" class="mt-1 block w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                             @error('logo_size')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>