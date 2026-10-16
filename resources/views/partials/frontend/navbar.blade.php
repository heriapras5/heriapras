@php
    $headerName = 'Heri A Pras';
    $homeUrl = route('home');
@endphp
<nav id="navbar" class="sticky top-0 z-50 bg-abyss/90 backdrop-blur border-b border-white/5">
    <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between gap-4">
        <a href="{{ $homeUrl }}" class="flex items-center gap-2" aria-label="{{ $headerName }} - Home">
            <span class="text-primary font-extrabold text-lg" aria-hidden="true">&lt;/&gt;</span>
            <span class="font-extrabold text-lg text-white tracking-tight">{{ $headerName }}</span>
        </a>
        <div class="flex items-center gap-7">
            <div class="hidden lg:flex items-center gap-7">
                <a href="{{ $homeUrl }}#hero" data-spy class="nav-link active">Home</a>
                <a href="{{ route('portfolio.index') }}" class="nav-link">Portofolio</a>
            </div>
            <button id="nav-toggle" type="button" class="lg:hidden w-10 h-10 rounded-lg border border-white/10 inline-flex items-center justify-center text-white" aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu navigasi">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="hidden lg:hidden border-t border-white/5 bg-night">
        <div class="max-w-6xl mx-auto px-5 py-3 flex flex-col">
            <a href="{{ $homeUrl }}#hero" class="nav-link py-2.5 border-b border-white/5">Home</a>
            <a href="{{ route('portfolio.index') }}" class="nav-link py-2.5">Portofolio</a>
        </div>
    </div>
</nav>
