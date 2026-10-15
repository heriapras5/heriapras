@php
    $fullName = setting('full_name') ?: setting('site_name') ?: 'Heri Agus Prasetyo';
    $profilePhoto = setting('profile_photo');
    $logoImage = setting('logo_image');
    $sidebarImage = $profilePhoto ?: $logoImage;
    $homeUrl = route('home');
@endphp
<header id="header" class="header dark-background d-flex flex-column">
    <i class="header-toggle d-xl-none bi bi-list" role="button" tabindex="0" aria-label="Buka menu navigasi"></i>

    <div class="sidebar-brand text-center">
        <a href="{{ $homeUrl }}" class="sidebar-logo" aria-label="{{ $fullName }}">
            @if($sidebarImage)
                <img src="{{ asset($sidebarImage) }}" alt="Foto {{ $fullName }}" class="img-fluid rounded-circle sidebar-profile-img" onerror="this.style.display='none'">
            @else
                <img src="{{ asset('assets/img/logo-default.svg') }}" alt="Logo {{ $fullName }}" class="sidebar-logo-img" onerror="this.style.display='none'">
            @endif
        </a>
        <a href="{{ $homeUrl }}" class="logo d-flex align-items-center justify-content-center">
            <h1 class="sitename">{{ $fullName }}</h1>
        </a>
    </div>

    <div class="social-links text-center">
        @if(setting('github_url'))
            <a href="{{ setting('github_url') }}" class="github" target="_blank" rel="noopener" aria-label="GitHub"><i class="bi bi-github"></i></a>
        @endif
        @if(setting('social_linkedin'))
            <a href="{{ setting('social_linkedin') }}" class="linkedin" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        @endif
        @if(setting('social_instagram'))
            <a href="{{ setting('social_instagram') }}" class="instagram" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        @endif
        @if(setting('whatsapp_number'))
            <a href="https://wa.me/{{ setting('whatsapp_number') }}" class="whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        @endif
        @if(setting('social_youtube'))
            <a href="{{ setting('social_youtube') }}" class="youtube" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
        @endif
    </div>

    <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="{{ $homeUrl }}#hero" class="active"><i class="bi bi-house navicon"></i>Beranda</a></li>
            <li><a href="{{ $homeUrl }}#about"><i class="bi bi-person navicon"></i>Tentang</a></li>
            <li><a href="{{ $homeUrl }}#skills"><i class="bi bi-lightbulb navicon"></i>Keahlian</a></li>
            <li><a href="{{ $homeUrl }}#resume"><i class="bi bi-file-earmark-text navicon"></i>Perjalanan</a></li>
            <li><a href="{{ $homeUrl }}#portfolio"><i class="bi bi-images navicon"></i>Portfolio</a></li>
            <li><a href="{{ $homeUrl }}#contact"><i class="bi bi-envelope navicon"></i>Kontak</a></li>
        </ul>
    </nav>

    <div class="sidebar-copyright mt-auto text-center">
        <p>© {{ date('Y') }} <strong>{{ $fullName }}</strong></p>
        <p>Hak Cipta Dilindungi Undang-Undang.</p>
    </div>
</header>
