@php
    $portfolioTabs = $projects->flatMap(fn ($p) => $p->categories)->unique('id')->values();
@endphp
<section id="portfolio" class="bg-night border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-20">
        <div class="reveal text-center max-w-xl mx-auto">
            <h2 class="section-title mt-2">{{ setting('landing_portfolio_title') ?: 'Portfolio' }}</h2>
            <p class="mt-2 text-[15px] text-muted">{{ setting('landing_portfolio_subtitle') ?: 'Berikut adalah beberapa proyek website dan aplikasi yang telah saya bangun.' }}</p>
        </div>
        @if($portfolioTabs->count() > 1)
        <div class="reveal mt-6 flex flex-nowrap justify-start md:justify-center gap-2 overflow-x-auto tabs-scroll max-w-full px-1 py-1" role="tablist" aria-label="Filter kategori portfolio">
            <button type="button" data-portfolio-filter="all" data-portfolio-active="true" class="portfolio-tab portfolio-tab-active shrink-0 whitespace-nowrap">Semua</button>
            @foreach($portfolioTabs as $tab)
            <button type="button" data-portfolio-filter="{{ $tab->slug ?: \Illuminate\Support\Str::slug($tab->name) }}" class="portfolio-tab shrink-0 whitespace-nowrap">{{ $tab->name }}</button>
            @endforeach
        </div>
        @endif
        <div class="reveal carousel-row mt-8">
            <button id="portfolio-prev" type="button" class="carousel-btn" aria-label="Geser ke project sebelumnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <div id="portfolio-track" class="portfolio-track" tabindex="0" aria-label="Daftar project, geser untuk melihat lainnya">
            @if($projects->count())
                {{-- Set card di-render 2x (set kedua = klon aria-hidden) agar marquee desktop muter mulus tanpa lompat balik.
                     Klon disembunyikan di mobile (CSS) dan saat filter tab aktif (JS). --}}
                @foreach($projects as $project)
                    @include('partials.frontend.project-card', ['project' => $project, 'isClone' => false])
                @endforeach
                @foreach($projects as $project)
                    @include('partials.frontend.project-card', ['project' => $project, 'isClone' => true])
                @endforeach
            @else
                <div class="w-full text-center py-8"><p class="text-sm text-muted">Project belum tersedia. Silakan tambahkan melalui panel admin.</p></div>
            @endif
            </div>
            <button id="portfolio-next" type="button" class="carousel-btn" aria-label="Geser ke project berikutnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
        @if($projects->count())
        <div class="reveal mt-8 text-center">
            <a href="{{ route('portfolio.index') }}" class="btn-pill btn-outline">Selengkapnya</a>
        </div>
        @endif
    </div>
</section>
