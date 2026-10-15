<section id="about" class="bg-night border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-20">
        <div class="reveal max-w-3xl">
            <p class="eyebrow-pill">{{ setting('ui_eyebrow_about') ?: 'Tentang Saya' }}</p>
            <h2 class="section-title mt-2">{{ setting('landing_about_title') ?: 'Saya bersemangat menciptakan solusi digital' }}</h2>
            <p class="mt-3 text-[15px] leading-relaxed">
                @if($aboutSection && $aboutSection->subtitle)
                    {{ $aboutSection->subtitle }}
                @else
                    {{ $bio }}
                @endif
            </p>
            @if($aboutSection && $aboutSection->description)
                <div class="rich-copy mt-3 text-[15px]">{!! cleanRichText($aboutSection->description) !!}</div>
            @endif
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('home') }}#resume" class="btn-pill btn-outline">
                    {{ setting('ui_cta_about') ?: 'Selengkapnya Tentang Saya' }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                @if(setting('footer_email'))
                    <a href="mailto:{{ setting('footer_email') }}" class="btn-pill btn-outline">{{ setting('ui_cta_hire') ?: 'Hubungi Saya' }}</a>
                @endif
            </div>
            <dl class="mt-7 grid gap-2 sm:grid-cols-2 text-sm text-muted">
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0">Studi:</dt><dd>{{ $studyProgram }}</dd></div>
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0">Kampus:</dt><dd>{{ $university }}</dd></div>
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0">Lokasi:</dt><dd>{{ $location }}</dd></div>
                @if(setting('footer_email'))
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0">Email:</dt><dd class="break-all">{{ setting('footer_email') }}</dd></div>
                @endif
            </dl>
        </div>
    </div>
</section>
