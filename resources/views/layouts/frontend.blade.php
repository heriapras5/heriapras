<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('seo_title') ?: 'Heri Agus Prasetyo - Web Developer & Software Engineer')</title>
    <meta name="description" content="@yield('meta_description', setting('seo_description') ?: setting('site_description', 'Portfolio pribadi Heri Agus Prasetyo, Web Developer dan Software Engineer.'))">
    @if(setting('seo_keywords'))<meta name="keywords" content="{{ setting('seo_keywords') }}">@endif
    @if(setting('seo_author'))<meta name="author" content="{{ setting('seo_author') }}">@endif
    <meta name="robots" content="{{ setting('seo_robots', 'index,follow') }}">
    <link rel="canonical" href="{{ setting('seo_canonical') ?: url()->current() }}">
    @php
        $siteName = setting('site_name', 'Heri Agus Prasetyo');
        $siteDescription = setting('site_description', 'Portfolio pribadi Heri Agus Prasetyo.');
        $ogTitle = trim(strip_tags((string) View::yieldContent('title', $siteName . ' - ' . setting('role', 'Web Developer & Software Engineer'))));
        $ogDescription = trim(strip_tags((string) View::yieldContent('meta_description', $siteDescription)));
        // Sufiks '|og-dark-v1': pengganjal versi agar platform share mengambil
        // ulang gambar setelah latar diganti hitam (URL berubah = cache baru).
        $ogImage = url('/og-image.png') . '?v=' . md5((string) (setting('profile_photo') ?: $siteName) . '|og-dark-v1');
    @endphp
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    @php $faviconVersion = faviconVersion(); @endphp
    <link rel="icon" type="image/png" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ url('/favicon.png') }}?v={{ $faviconVersion }}">
    <script type="application/ld+json">{!! personJsonLd() !!}</script>
    @vite(['resources/css/frontend.css'])
    @stack('styles')
    @if(setting('seo_google_analytics'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('seo_google_analytics') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('seo_google_analytics') }}');</script>
    @endif
</head>
<body class="@yield('body_class')">
    <script>document.documentElement.classList.add('js');</script>
    <a href="#main-content" class="skip-link">Langsung ke konten</a>
    @include('partials.frontend.navbar')
    <main id="main-content" class="main">@yield('content')</main>
    <x-footer />
    <a href="#" id="scroll-top" class="scroll-top" aria-label="Kembali ke atas"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg></a>
    @vite(['resources/js/frontend.js'])
    @stack('scripts')
    @if(setting('whatsapp_number'))
    <script>window.__waNumber = @js(setting('whatsapp_number'));</script>
    @endif
</body>
</html>
