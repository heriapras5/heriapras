<section id="portfolio" class="bg-night border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-20">
        <div class="reveal text-center max-w-xl mx-auto">
            <h2 class="section-title mt-2">{{ setting('landing_portfolio_title') ?: 'Portfolio' }}</h2>
            <p class="mt-2 text-[15px] text-muted">{{ setting('landing_portfolio_subtitle') ?: 'Berikut adalah beberapa proyek website dan aplikasi yang telah saya bangun.' }}</p>
        </div>
        <div class="reveal carousel-row mt-8">
            <button id="portfolio-prev" type="button" class="carousel-btn" aria-label="Geser ke project sebelumnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <div id="portfolio-track" class="portfolio-track" tabindex="0" aria-label="Daftar project, geser untuk melihat lainnya">
            @if($projects->count())
                {{-- Set card di-render 2x (set kedua = klon aria-hidden) agar marquee muter mulus tanpa lompat balik --}}
                @for($repeat = 0; $repeat < 2; $repeat++)
            @foreach($projects as $project)
                @php
                    $isClone = $repeat === 1;
                    $projectCategory = $project->categories->first();
                    $filterClass = $projectCategory ? ($projectCategory->slug ?: \Illuminate\Support\Str::slug($projectCategory->name)) : 'lainnya';
                    $cover = $project->coverImage();
                    $coverExists = $cover && $cover->image
                        && ! str_starts_with((string) $cover->image, 'http')
                        && is_file(public_path(ltrim((string) $cover->image, '/')));
                @endphp
                <article data-portfolio-item @if(!$isClone) data-portfolio-original @endif class="portfolio-card filter-{{ $filterClass }} card-soft card-hover overflow-hidden flex flex-col @if(!$isClone) reveal @endif" @if($isClone) aria-hidden="true" @endif>
                    <div class="relative">
                        @if($coverExists)
                            <img src="{{ asset($cover->image) }}" alt="{{ $isClone ? '' : 'Screenshot '.$project->name }}" @if(!$isClone) loading="lazy" @endif class="w-full aspect-[16/10] object-cover" onerror="this.style.display='none';if(this.nextElementSibling)this.nextElementSibling.style.display='flex'">
                            <div class="w-full aspect-[16/10] bg-primary/20 flex-col items-center justify-center gap-1 p-4 text-center" style="display:none" aria-hidden="true">
                                <span class="font-bold text-white">{{ \Illuminate\Support\Str::limit($project->name, 28) }}</span>
                                <small class="text-muted">{{ $projectCategory->name ?? 'Projek' }}</small>
                            </div>
                        @else
                            <div class="w-full aspect-[16/10] bg-primary/20 flex flex-col items-center justify-center gap-1 p-4 text-center">
                                <span class="font-bold text-white">{{ \Illuminate\Support\Str::limit($project->name, 28) }}</span>
                                <small class="text-muted">{{ $projectCategory->name ?? 'Projek' }}</small>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-bold text-white text-[15px] leading-tight">{{ $project->name }}</h3>
                        <p class="text-[13px] text-muted mt-1 flex-1">{{ \Illuminate\Support\Str::limit(strip_tags($project->description ?: ($projectCategory->name ?? 'Project')), 90) }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs font-bold text-muted">{{ $projectCategory->name ?? 'Projek' }}{{ $project->year ? ' · '.$project->year : '' }}</span>
                            <a href="{{ route('projects.detail', $project->slug) }}" @if($isClone) tabindex="-1" @endif class="inline-flex items-center gap-1.5 text-xs font-bold text-glow hover:text-white" aria-label="Lihat detail {{ $project->name }}">
                                {{ setting('ui_label_view_project') ?: 'Lihat Detail' }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
                @endfor
            @else
                <div class="w-full text-center py-8"><p class="text-sm text-muted">Project belum tersedia. Silakan tambahkan melalui panel admin.</p></div>
            @endif
            </div>
            <button id="portfolio-next" type="button" class="carousel-btn" aria-label="Geser ke project berikutnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</section>
