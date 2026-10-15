@if($skills->count())
<section id="skills" class="bg-abyss border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-20">
        <div class="reveal text-center max-w-xl mx-auto">
            <p class="eyebrow">{{ setting('ui_eyebrow_skills') ?: 'Keahlian Saya' }}</p>
            <h2 class="section-title mt-2">{{ setting('landing_skills_title') ?: 'Teknologi yang Saya Kuasai' }}</h2>
            <p class="mt-2 text-sm text-muted">{{ setting('landing_skills_subtitle') ?: 'Teknologi yang saya gunakan untuk mewujudkan ide menjadi produk nyata.' }}</p>
        </div>
        <div class="mt-10 grid gap-x-10 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($skills as $skill)
                @php
                    $iconMarkup = techIcon($skill->title, safeIcon($skill->icon) ?: '<span class="text-xs font-extrabold">•</span>');
                    $isBrandIcon = str_contains($iconMarkup, 'devicon');
                    $brandColor = $isBrandIcon ? null : techBrandColor($skill->title);
                @endphp
                <div class="reveal" data-skill-item>
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-md inline-flex items-center justify-center shrink-0 [&>svg]:w-5 [&>svg]:h-5 [&>i]:text-[20px] [&>i]:leading-none {{ $brandColor ? '' : ($isBrandIcon ? '' : 'bg-card border border-white/10 text-glow') }}" aria-hidden="true" @if($brandColor) style="color: {{ $brandColor }}" @endif>{!! $iconMarkup !!}</span>
                        <h3 class="font-semibold text-white text-sm">{{ $skill->title }}</h3>
                        <span class="ml-auto text-xs font-bold text-muted">{{ $skill->percent ?? 80 }}%</span>
                    </div>
                    <div class="skill-track mt-2.5" role="progressbar" aria-valuenow="{{ $skill->percent ?? 80 }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $skill->title }}">
                        <div class="skill-fill" data-skill-fill="{{ $skill->percent ?? 80 }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
