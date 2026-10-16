@extends('layouts.frontend')
@section('title', fillTitle(setting('tab_project') ?: '{project} - Heri A Pras', ['project' => $project->name, 'site' => setting('site_name', 'Heri A Pras'), 'nama' => setting('full_name', 'Heri Agus Prasetyo')]))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($project->description ?: 'Project oleh Heri Agus Prasetyo.'), 155))
@section('body_class', 'portfolio-details-page')

@section('content')
@php
    $cover = $project->coverImage();
    $gallery = $project->images->where('type', 'gallery')->values();
    $technologies = $project->technologies->pluck('name')->filter()->values();
    $category = $project->categories->first();
    $slides = collect();
    if ($cover) {
        $slides->push(['image' => $cover->image, 'caption' => $cover->caption ?? null]);
    }
    foreach ($gallery as $image) {
        $slides->push(['image' => $image->image, 'caption' => $image->caption ?? null]);
    }
@endphp

@include('partials.frontend.page-title', ['title' => $project->name, 'breadcrumb' => $project->name])

<section class="bg-abyss">
    <div class="max-w-6xl mx-auto px-5 pt-8 pb-12 md:pt-10 md:pb-16 grid gap-6 lg:grid-cols-[1.6fr_1fr] items-start">
        <div class="reveal card-soft overflow-hidden">
            @if($slides->count())
            <div id="project-gallery" class="project-gallery" data-slide-count="{{ $slides->count() }}">
                <div class="project-gallery-viewport">
                    <div id="project-gallery-track" class="project-gallery-track">
                        @foreach($slides as $slide)
                        <div class="project-gallery-slide">
                            <img src="{{ asset($slide['image']) }}" alt="Screenshot {{ $project->name }}{{ $slides->count() > 1 ? ' '.$loop->iteration : '' }}" @if(!$loop->first) loading="lazy" @endif class="w-full aspect-[16/10] object-cover cursor-zoom-in" data-gallery-open="{{ $loop->index }}" onerror="this.style.display='none'">
                        </div>
                        @endforeach
                    </div>
                </div>
                @if($slides->count() > 1)
                <button id="project-gallery-prev" type="button" class="project-gallery-nav project-gallery-prev" aria-label="Gambar sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button id="project-gallery-next" type="button" class="project-gallery-nav project-gallery-next" aria-label="Gambar berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <button id="project-gallery-expand" type="button" class="project-gallery-expand" aria-label="Perbesar gambar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
                </button>
                <span id="project-gallery-counter" class="project-gallery-counter">1 / {{ $slides->count() }}</span>
                @endif
            </div>
            <div id="project-lightbox" class="project-lightbox" hidden role="dialog" aria-modal="true" aria-label="Pratinjau gambar {{ $project->name }}">
                <button id="project-lightbox-close" type="button" class="project-lightbox-close" aria-label="Tutup pratinjau">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
                <button id="project-lightbox-prev" type="button" class="project-gallery-nav project-gallery-prev" aria-label="Gambar sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <img id="project-lightbox-image" src="" alt="">
                <button id="project-lightbox-next" type="button" class="project-gallery-nav project-gallery-next" aria-label="Gambar berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <span id="project-lightbox-counter" class="project-lightbox-counter"></span>
            </div>
            @else
                <div class="w-full aspect-[16/10] bg-primary/20 flex flex-col items-center justify-center gap-1 p-6 text-center">
                                <span class="font-bold text-white text-lg">{{ $project->name }}</span>
                                <small class="text-muted">{{ $category->name ?? 'Projek' }}</small>
                </div>
            @endif
            <div class="p-6 border-t border-white/5">
                <h2 class="font-extrabold text-white text-lg">Deskripsi</h2>
                <div class="rich-copy mt-3 text-[15px] text-justify">
                    @if($project->description)
                        {!! cleanRichText($project->description) !!}
                    @else
                        <p>Belum ada deskripsi untuk projek ini.</p>
                    @endif
                </div>
                @if($project->about)
                    <div class="rich-copy mt-4 text-[15px] text-justify">{!! cleanRichText(is_array($project->about) ? implode('</p><p>', $project->about) : $project->about) !!}</div>
                @endif
                @if($project->result_text)
                    <p class="mt-4 text-sm text-muted text-justify"><strong class="text-white">Hasil:</strong> {{ $project->result_text }}</p>
                @endif
            </div>
        </div>

        <aside class="reveal card-soft p-6 lg:sticky lg:top-24">
            <h3 class="font-extrabold text-white">Informasi Portofolio</h3>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Kategori</dt><dd class="text-muted">{{ $category->name ?? 'Projek' }}</dd></div>
                @if($project->client)
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Klien</dt><dd class="text-muted">{{ $project->client }}</dd></div>
                @endif
                @if($project->year)
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Tahun</dt><dd class="text-muted">{{ $project->year }}</dd></div>
                @endif
                @if($project->url)
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Link Demo</dt><dd class="break-all"><a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-glow hover:text-white">{{ parse_url($project->url, PHP_URL_HOST) ?: $project->url }}</a></dd></div>
                @endif
                @if($technologies->count())
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Teknologi</dt><dd class="text-muted">{{ $technologies->join(', ') }}</dd></div>
                @endif
            </dl>
            @if($technologies->count())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($technologies as $tech)
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-md bg-primary/15 border border-white/10 text-glow">{{ $tech }}</span>
                    @endforeach
                </div>
            @endif
            <a href="{{ route('home') }}#portfolio" class="btn-pill btn-outline w-full mt-6">Kembali ke Portofolio</a>
        </aside>
    </div>
</section>

@if($project->features->count())
<section class="bg-night border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-12">
        <h2 class="section-title mt-2">Fitur-Fitur</h2>
        <ul class="mt-6 grid gap-4 md:grid-cols-2">
            @foreach($project->features as $feature)
                <li class="card-soft p-5">
                    <strong class="text-white">{{ $feature->feature }}</strong>
                    @if($feature->description)<p class="text-sm mt-1.5 leading-relaxed">{{ $feature->description }}</p>@endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

@if($relatedProjects->count())
<section class="bg-abyss border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-12">
        <h2 class="section-title mt-2">Portofolio Terkait</h2>
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($relatedProjects as $related)
                @include('partials.frontend.project-card', ['project' => $related, 'isClone' => false])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
