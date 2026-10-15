<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ $siteName = setting('site_name', 'Heri Agus Prasetyo') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $siteName = setting('site_name', 'Heri Agus Prasetyo');
        $logoImage = setting('logo_image');
        $logoSvg = setting('logo_icon');
        $siteTagline = setting('site_tagline', 'INOVASI WEBSITE');
    @endphp
    @php $faviconVersion = faviconVersion(); @endphp
    <link rel="icon" type="image/png" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    @if(setting('logo_image'))
    <link rel="apple-touch-icon" href="{{ asset(setting('logo_image')) }}">
    @else
    <link rel="apple-touch-icon" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    @endif
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(145deg, #060d24 0%, #0c1a3a 25%, #143264 50%, #1c4a8c 75%, #2563eb 100%);
        }

        .deco-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
            pointer-events: none;
        }

        .deco-dot {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            pointer-events: none;
        }

        .dot-grid {
            position: absolute;
            pointer-events: none;
        }
        .dot-grid::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.18) 1.5px, transparent 1.5px);
            background-size: 22px 22px;
        }

        .deco-angular {
            position: absolute;
            pointer-events: none;
        }

        .deco-thin-line {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            pointer-events: none;
            transform: rotate(-30deg);
        }

        .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <!-- Geometric decorations -->
    <div class="deco-ring w-[500px] h-[500px] -top-32 -left-32"></div>
    <div class="dot-grid w-[180px] h-[120px] top-8 right-12"></div>
    <div class="deco-dot w-24 h-24 top-1/2 -left-6 -translate-y-1/2"></div>
    <div class="deco-dot w-16 h-16 top-[40%] left-[15%] opacity-60"></div>
    <div class="dot-grid w-[140px] h-[100px] bottom-16 left-8"></div>
    <div class="deco-angular bottom-0 right-0 w-[350px] h-[350px]">
        <svg viewBox="0 0 350 350" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <polygon points="350,350 150,350 350,100" fill="rgba(255,255,255,0.04)"/>
            <polygon points="350,350 250,350 350,200" fill="rgba(255,255,255,0.03)"/>
            <polygon points="350,350 100,350 200,250 350,150" fill="rgba(255,255,255,0.02)"/>
        </svg>
    </div>
    <div class="deco-thin-line w-[400px] top-[15%] -left-10"></div>
    <div class="deco-thin-line w-[300px] bottom-[20%] right-[10%]"></div>
    <div class="deco-ring w-[200px] h-[200px] bottom-24 right-16"></div>

    <!-- Login card -->
    <div class="w-full max-w-[400px] relative z-10" x-data="{ showPassword: false }">
        <div class="bg-white rounded-[16px] shadow-xl shadow-black/10 px-7 py-7">
            <!-- Logo & Brand -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-600/25 mb-3">
                    @if($logoImage)
                    <img src="{{ asset($logoImage) }}" alt="{{ $siteName }}" class="h-10 w-auto rounded-lg object-cover">
                    @elseif($logoSvg)
                    <div class="text-white flex items-center justify-center">{!! $logoSvg !!}</div>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    @endif
                </div>
                <h1 class="text-lg font-bold text-[#0f172a] tracking-tight">{{ $siteName }}</h1>
                <p class="text-[10px] tracking-[0.25em] uppercase text-blue-600 font-semibold mt-1">{{ $siteTagline }}</p>
                <div class="w-10 h-0.5 bg-gradient-to-r from-transparent via-blue-500 to-transparent rounded-full mt-4"></div>
            </div>

            <p class="text-center text-xs text-[#94a3b8] mb-5">Masuk untuk mengelola website dengan mudah dan aman.</p>

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-3.5 py-2.5 rounded-lg text-xs flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-3.5 py-2.5 rounded-lg text-xs flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ $errors->first('email') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1">Email</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full pl-9 pr-3.5 py-2.5 border border-[#e2e8f0] rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow"
                               required autofocus placeholder="Masukkan email Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1">Password</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input :type="showPassword ? 'text' : 'password'" name="password"
                               class="w-full pl-9 pr-9 py-2.5 border border-[#e2e8f0] rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow"
                               required placeholder="Masukkan password Anda">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#64748b] transition-colors">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-1.5 text-xs text-[#64748b] cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-white py-2.5 rounded-xl text-xs font-semibold transition-all shadow-md shadow-blue-600/20 flex items-center justify-center gap-1.5 mt-1">
                    Masuk
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

        </div>

        <p class="text-center text-white/60 text-[10px] mt-5">&copy; {{ date('Y') }} <span class="text-blue-300 font-medium">{{ $siteName }}</span>. All rights reserved.</p>
    </div>
</body>
</html>