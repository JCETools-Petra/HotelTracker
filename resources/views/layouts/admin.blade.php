<<<<<<< HEAD
{{-- resources/views/layouts/admin.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Panel') }}
            </h2>
            <nav>
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Admin') }}
                </x-nav-link>
                {{-- Tambahkan link navigasi admin lainnya di sini --}}
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 rounded-md p-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 font-medium text-sm text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-700 border border-blue-400 dark:border-blue-600 rounded-md p-3">
                            {{ session('info') }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
=======
{{-- resources/views/layouts/admin.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Panel') }}
            </h2>
            <nav>
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Admin') }}
                </x-nav-link>
                {{-- Tambahkan link navigasi admin lainnya di sini --}}
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 rounded-md p-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 font-medium text-sm text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-700 border border-blue-400 dark:border-blue-600 rounded-md p-3">
                            {{ session('info') }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
>>>>>>> d022ff7944e4652039483fc40f98e16fe7417648
</x-app-layout>