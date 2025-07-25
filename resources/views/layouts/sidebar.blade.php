<aside class="flex-shrink-0 w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700 flex flex-col">
    <div class="h-16 flex items-center justify-center border-b dark:border-gray-700 px-4">
        <a href="{{ route('dashboard') }}">
            {{-- ======================= AWAL BLOK YANG DIUBAH ======================= --}}
            @php
                // Menggunakan kunci 'logo_path' dan pengaturan ukuran 'sidebar_logo_size'
                $logoPath = $appSettings['logo_path'] ?? null;
                $sidebarLogoSize = $appSettings['sidebar_logo_size'] ?? 40; // Default 40px
                $appName = $appSettings['app_name'] ?? config('app.name', 'Laravel');
            @endphp

            @if($logoPath)
                {{-- Jika ada logo kustom, tampilkan --}}
                <img src="{{ asset('storage/' . $logoPath) }}" 
                     alt="App Logo" 
                     style="height: {{ $sidebarLogoSize }}px;"
                     class="w-auto">
            @else
                {{-- Jika tidak, tampilkan nama aplikasi sebagai teks --}}
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $appName }}</span>
            @endif
            {{-- ======================= AKHIR BLOK YANG DIUBAH ====================== --}}
        </a>
    </div>

    <nav class="flex-grow p-4 space-y-2">
        @auth
            {{-- ============================ --}}
            {{--    MENU UNTUK ADMIN & OWNER    --}}
            {{-- ============================ --}}
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
                <x-side-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </x-slot>
                    {{ __('Dashboard Admin') }}
                </x-side-nav-link>
                
                <x-side-nav-link :href="route('admin.kpi.analysis')" :active="request()->routeIs('admin.kpi.analysis')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </x-slot>
                    {{ __('Pusat Analisis Kinerja') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.revenue-targets.index')" :active="request()->routeIs('admin.revenue-targets.*')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </x-slot>
                    {{ __('Manajemen Target') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.sales.analytics')" :active="request()->routeIs('admin.sales.analytics')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </x-slot>
                    {{ __('Analisis Sales') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.properties.compare_page')" :active="request()->routeIs('admin.properties.compare.*')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </x-slot>
                    {{ __('Bandingkan Properti') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0112 12.75a5.995 5.995 0 01-3 5.197m0 0A7.963 7.963 0 0012 21a7.963 7.963 0 003-5.197M15 21a6 6 0 00-9-5.197"></path></svg>
                    </x-slot>
                    {{ __('Manajemen Pengguna') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.properties.index')" :active="request()->routeIs('admin.properties.index') || request()->routeIs('admin.properties.create') || request()->routeIs('admin.properties.edit') || request()->routeIs('admin.properties.show')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </x-slot>
                    {{ __('Manajemen Properti') }}
                </x-side-nav-link>
                
                <x-side-nav-link :href="route('admin.mice-categories.index')" :active="request()->routeIs('admin.mice-categories.*')">
                    {{ __('Kategori MICE') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('admin.price-packages.index')" :active="request()->routeIs('admin.price-packages.*')">
                     <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </x-slot>
                    {{ __('Manajemen Harga') }}
                </x-side-nav-link>
                <x-side-nav-link :href="route('admin.activity_log.index')" :active="request()->routeIs('admin.activity_log.index')">
                     <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </x-slot>
                    {{ __('Log Aktivitas') }}
                </x-side-nav-link>
                <x-side-nav-link :href="route('admin.calendar.index')" :active="request()->routeIs('admin.calendar.index')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </x-slot>
                    {{ __('Event Calendar') }}
                </x-side-nav-link>
                <x-side-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                 <x-slot name="icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </x-slot>
                {{ __('Pengaturan') }}
                </x-side-nav-link>

            {{-- ================== --}}
            {{--    MENU UNTUK SALES    --}}
            {{-- ================== --}}
            @elseif(Auth::user()->role === 'sales')
                <x-side-nav-link :href="route('sales.dashboard')" :active="request()->routeIs('sales.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </x-slot>
                    {{ __('Dashboard') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('sales.bookings.index')" :active="request()->routeIs('sales.bookings.*')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </x-slot>
                    {{ __('Master Booking') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('sales.calendar.index')" :active="request()->routeIs('sales.calendar.index')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </x-slot>
                    {{ __('Event Calendar') }}
                </x-side-nav-link>
            
            {{-- ============================ --}}
            {{--    MENU UNTUK PENGGUNA PROPERTI    --}}
            {{-- ============================ --}}
            @elseif(Auth::user()->role === 'pengguna_properti')
                <x-side-nav-link :href="route('property.dashboard')" :active="request()->routeIs('property.dashboard')">
                    <x-slot name="icon">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </x-slot>
                    {{ __('Dashboard Properti') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('property.income.index')" :active="request()->routeIs('property.income.*')">
                     <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </x-slot>
                    {{ __('Riwayat Pendapatan') }}
                </x-side-nav-link>
                @elseif(Auth::user()->role === 'online_ecommerce')
                <x-side-nav-link :href="route('ecommerce.dashboard')" :active="request()->routeIs('ecommerce.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </x-slot>
                    {{ __('Dashboard') }}
                </x-side-nav-link>
            @endif
        @endauth
    </nav>
</aside>