<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin {{ setting('site_name', 'Heri Agus Prasetyo') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="status-toggle-url" content="{{ Route::has('admin.status.toggle') ? route('admin.status.toggle') : '' }}">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/admin-summernote.css', 'resources/js/app.js', 'resources/js/admin.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php $faviconVersion = faviconVersion(); @endphp
    <link rel="icon" type="image/png" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    @if(setting('logo_image'))
    <link rel="apple-touch-icon" href="{{ asset(setting('logo_image')) }}">
    @else
    <link rel="apple-touch-icon" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    @endif
    @stack('styles')
</head>
<body x-data="{
        userDropdown: false,
        sidebarMobile: false,
        sidebarOpen: localStorage.getItem('portfolio.sidebarOpen') !== '0',
      }"
      x-init="$watch('sidebarOpen', v => localStorage.setItem('portfolio.sidebarOpen', v ? '1' : '0'))"
      :class="{ 'sidebar-toggled': sidebarMobile }"
      class="bg-[#f1f5f9] font-sans text-[#1e293b] h-screen overflow-hidden">

    <!-- Flash Message: satu komponen untuk success/error/warning/info -->
    <div class="fixed top-4 right-4 z-[100] max-w-sm w-full space-y-2">
        <x-flash-message type="success" :message="session('success')" />
        <x-flash-message type="error" :message="session('error')" />
        <x-flash-message type="warning" :message="session('warning')" />
        <x-flash-message type="info" :message="session('info')" />
    </div>

    <!-- Mobile Overlay -->
    <div @click="sidebarMobile = false" class="sidebar-overlay"></div>

    @php
    $navGroups = [
        ['label' => 'Menu Utama', 'items' => [
            ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'icon' => 'dashboard', 'active' => request()->routeIs('admin.dashboard')],
        ]],
        ['label' => 'Hero', 'items' => [
            ['label' => 'Teknologi Hero', 'route' => route('admin.services.index'), 'icon' => 'paket', 'active' => request()->routeIs('admin.services*')],
        ]],
        ['label' => 'Portfolio', 'items' => [
            ['label' => 'Kategori Project', 'route' => route('admin.project-categories.index'), 'icon' => 'tag', 'active' => request()->routeIs('admin.project-categories*')],
            ['label' => 'Project', 'route' => route('admin.projects.index'), 'icon' => 'folder', 'active' => request()->routeIs('admin.projects*')],
        ]],
        ['label' => 'Sistem', 'items' => [
            ['label' => 'Pengaturan', 'route' => route('admin.settings.index'), 'icon' => 'settings', 'active' => request()->routeIs('admin.settings*')],
        ]],
    ];
    @endphp

    <div class="flex h-full min-h-0">
        <!-- ========== SIDEBAR ========== -->
        <aside class="admin-sidebar text-white flex flex-col h-full shrink-0" :class="{ 'sidebar-collapsed': !sidebarOpen }">
            <div class="shrink-0">
                <!-- Logo -->
                @php $logoImage = setting('logo_image'); @endphp
                <div class="px-6 pt-6 pb-4 sidebar-logo-wrap">
                    <a href="{{ url('/admin') }}" class="flex items-center gap-3">
                        @if($logoImage)
                        <img src="{{ asset($logoImage) }}" alt="{{ setting('site_name', 'Heri Agus Prasetyo') }}" class="w-9 h-9 rounded-xl object-cover shadow-lg shadow-blue-600/20 shrink-0">
                        @endif
                        <div class="min-w-0 sidebar-text">
                            <span class="block text-lg font-bold tracking-wide text-white truncate leading-tight">{!! nl2br(e(setting('site_name', 'Heri Agus Prasetyo'))) !!}</span>
                            <span class="block text-[0.65rem] tracking-[0.2em] text-blue-300/80 uppercase font-semibold truncate leading-tight mt-0.5">{{ setting('site_tagline', 'WEB DEVELOPER & SOFTWARE ENGINEER') }}</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-3 pb-3 sidebar-scroll">
                @foreach($navGroups as $group)
                <div>
                    <div class="text-[0.55rem] tracking-[0.2em] text-white/40 uppercase font-semibold px-3 pt-4 pb-1.5 sidebar-text">{{ $group['label'] }}</div>
                    @foreach($group['items'] as $item)
                    <a href="{{ $item['route'] }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-white/70 hover:text-white {{ $item['active'] ? 'active' : '' }}">
                        <x-admin.nav-icon :name="$item['icon']" />
                        <span class="flex-1 sidebar-text">{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                        <span class="sidebar-collapse-hide bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
                @endforeach

            </nav>
        </aside>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="flex-1 flex flex-col min-w-0 min-h-0">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200/60 px-4 lg:px-6 py-3 flex items-center justify-between shrink-0 shadow-sm">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" @click="window.innerWidth >= 1024 ? (sidebarOpen = !sidebarOpen) : (sidebarMobile = !sidebarMobile)"
                            class="text-[#64748b] hover:text-blue-600 transition-colors" aria-label="Toggle sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative" @click.away="userDropdown = false">
                        <button @click="userDropdown = !userDropdown" class="flex items-center gap-2 pl-2 border-l border-gray-200 hover:opacity-80 transition-opacity">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:flex items-center gap-1">
                                <span class="text-sm text-[#64748b]">{{ auth()->user()->name }}</span>
                                <svg :class="userDropdown ? 'rotate-180' : ''" class="text-[#94a3b8] transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </button>
                        <div x-show="userDropdown" x-cloak
                             @click.away="userDropdown = false"
                             class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#1e293b] truncate leading-tight">{{ auth()->user()->name }}</p>
                                        <p class="text-[10px] uppercase tracking-wide font-semibold text-blue-600 mt-0.5">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-3 text-sm text-[#1e293b] hover:bg-gray-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-[#94a3b8]"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Pengaturan
                            </a>
                            <hr class="border-gray-100">
                            <button @click="window.confirmLogout()" class="w-full flex items-center gap-2.5 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto min-h-0 p-4 lg:p-6 content-scroll">
                @yield('content')
            </div>

            @stack('modals')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    @stack('scripts')
</body>
</html>
