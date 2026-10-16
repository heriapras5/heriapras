@php
    $typedRoles = $typedRoles ?? (setting('hero_typed_roles') ?: 'Web Developer, Software Engineer');
    $firstName = explode(' ', trim($fullName))[0] ?? $fullName;
    $greeting = $firstName ?: $fullName;
    $profilePhotoExists = $profilePhoto
        && ! str_starts_with((string) $profilePhoto, 'http')
        && is_file(public_path(ltrim((string) $profilePhoto, '/')));
    $profilePhotoVersion = $profilePhotoExists ? filemtime(public_path(ltrim((string) $profilePhoto, '/'))) : null;
@endphp
<section id="hero" class="relative overflow-hidden bg-abyss">
    <div class="dots-grid absolute top-16 right-8 md:right-24 w-28 h-24 opacity-60" aria-hidden="true"></div>
    <div class="max-w-6xl mx-auto px-5 pt-12 pb-14 md:pt-16 md:pb-20 grid gap-12 lg:grid-cols-2 items-center relative">
        <div class="reveal">
            <p class="text-base text-body">Halo, Nama Saya</p>
            <h1 class="text-white font-extrabold leading-[1.12] text-4xl md:text-5xl">
                <span class="text-glow">{{ $fullName }}</span>
            </h1>
            <p class="mt-4 text-base leading-relaxed max-w-md">{{ $bio }}</p>
<p class="mt-2 text-[15px] text-muted">
    <span class="typed-roles font-semibold text-glow" data-typed-items="{{ $typedRoles }}">{{ $role }}</span>
</p>
            <div class="mt-9">
                <p class="text-[11px] font-bold tracking-[0.18em] uppercase text-muted">{{ setting('ui_hero_tech_label') ?: 'Teknologi yang saya gunakan' }}</p>
                <div class="mt-3 flex flex-wrap items-center gap-3" aria-label="Teknologi yang dikuasai">
                    @forelse($skills->take(7) as $skill)
                        @php
                            $iconMarkup = techIcon($skill->title, safeIcon($skill->icon) ?: '<span class="text-xs font-extrabold">•</span>');
                            $isBrandIcon = str_contains($iconMarkup, 'devicon');
                            $brandColor = $isBrandIcon ? null : techBrandColor($skill->title);
                        @endphp
                        <span class="w-9 h-9 inline-flex items-center justify-center [&>svg]:w-6 [&>svg]:h-6 [&>i]:text-[24px] [&>i]:leading-none {{ $brandColor ? '' : 'text-glow' }}" title="{{ $skill->title }}" @if($brandColor) style="color: {{ $brandColor }}" @endif>{!! $iconMarkup !!}</span>
                    @empty
                        @foreach(['HTML', 'CSS', 'JS', 'PHP', 'Laravel', 'MySQL', 'Git'] as $tech)
                            @php $fallbackColor = techBrandColor($tech); @endphp
                            <span class="h-9 px-3 rounded-lg inline-flex items-center justify-center text-xs font-extrabold {{ $fallbackColor ? '' : 'text-glow' }}" title="{{ $tech }}" @if($fallbackColor) style="color: {{ $fallbackColor }}" @endif>{{ $tech }}</span>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </div>
        <div class="reveal relative flex justify-center lg:justify-end">
            <div class="relative">
                <div class="w-72 h-72 md:w-[22rem] md:h-[22rem] rounded-full bg-primary overflow-hidden" aria-hidden="false">
                    @if($profilePhotoExists)
                        <img src="{{ asset($profilePhoto) }}{{ $profilePhotoVersion ? '?v='.$profilePhotoVersion : '' }}" alt="Foto {{ $fullName }}"
                             class="w-full h-full object-cover object-top"
                             onerror="this.style.display='none';if(this.nextElementSibling)this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full items-center justify-center bg-primary" style="display:none" aria-hidden="true">
                            <span class="font-extrabold text-7xl text-white">{{ $initials ?: 'HA' }}</span>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary" aria-hidden="true">
                            <span class="font-extrabold text-7xl text-white">{{ $initials ?: 'HA' }}</span>
                        </div>
                    @endif
                </div>
                <svg class="absolute -left-14 md:-left-20 bottom-6 hidden lg:block text-white/70" width="52" height="60" viewBox="0 0 52 60" fill="none" aria-hidden="true">
                    <path d="M46 4C40 14 30 20 20 22c-8 1.6-13 7-11 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="m6 30 3 7 7-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
        </div>
    </div>
</section>
