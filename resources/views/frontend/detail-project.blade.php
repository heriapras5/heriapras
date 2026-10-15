@extends('layouts.frontend')
@section('title', $project->name . ' - ' . setting('full_name', 'Heri Agus Prasetyo'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($project->description ?: 'Project oleh Heri Agus Prasetyo.'), 155))
@section('body_class', 'portfolio-details-page')

@section('content')
@php
    $cover = $project->coverImage();
    $gallery = $project->images->where('type', 'gallery')->values();
    $technologies = $project->technologies->pluck('name')->filter()->values();
    $category = $project->categories->first();
@endphp

@include('partials.frontend.page-title', ['title' => $project->name, 'breadcrumb' => $project->name])

<section class="bg-abyss">
    <div class="max-w-6xl mx-auto px-5 py-12 md:py-16 grid gap-6 lg:grid-cols-[1.6fr_1fr] items-start">
        <div class="reveal card-soft overflow-hidden">
            @if($cover)
                <img src="{{ asset($cover->image) }}" alt="Screenshot {{ $project->name }}" class="w-full aspect-[16/10] object-cover" loading="lazy" onerror="this.style.display='none'">
            @else
                <div class="w-full aspect-[16/10] bg-primary/20 flex flex-col items-center justify-center gap-1 p-6 text-center">
                                <span class="font-bold text-white text-lg">{{ $project->name }}</span>
                                <small class="text-muted">{{ $category->name ?? 'Projek' }}</small>
                </div>
            @endif
            @if($gallery->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 border-t border-white/5">
                    @foreach($gallery as $image)
                        <img src="{{ asset($image->image) }}" alt="{{ $project->name }} galeri {{ $loop->iteration }}" loading="lazy" class="w-full aspect-[4/3] object-cover rounded-lg border border-white/10" onerror="this.style.display='none'">
                    @endforeach
                </div>
            @endif
            <div class="p-6 border-t border-white/5">
                <h2 class="font-extrabold text-white text-lg">Deskripsi Projek</h2>
                <div class="rich-copy mt-3 text-[15px]">
                    @if($project->description)
                        {!! cleanRichText($project->description) !!}
                    @else
                        <p>Belum ada deskripsi untuk projek ini.</p>
                    @endif
                </div>
                @if($project->about)
                    <div class="rich-copy mt-4 text-[15px]">{!! cleanRichText(is_array($project->about) ? implode('</p><p>', $project->about) : $project->about) !!}</div>
                @endif
                @if($project->result_text)
                    <p class="mt-4 text-sm text-muted"><strong class="text-white">Hasil:</strong> {{ $project->result_text }}</p>
                @endif
            </div>
        </div>

        <aside class="reveal card-soft p-6 lg:sticky lg:top-24">
            <h3 class="font-extrabold text-white">Informasi Projek</h3>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Kategori</dt><dd class="text-muted">{{ $category->name ?? 'Projek' }}</dd></div>
                @if($project->client)
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Klien</dt><dd class="text-muted">{{ $project->client }}</dd></div>
                @endif
                @if($project->year)
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">Tahun</dt><dd class="text-muted">{{ $project->year }}</dd></div>
                @endif
                @if($project->url)
                    <div class="flex gap-2"><dt class="font-bold text-white shrink-0 w-24">URL</dt><dd class="break-all"><a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-glow hover:text-white">{{ parse_url($project->url, PHP_URL_HOST) ?: $project->url }}</a></dd></div>
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
        <p class="eyebrow">Fitur Unggulan</p>
        <h2 class="section-title mt-2">Yang dikerjakan dalam project ini</h2>
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

@if($prevProject || $nextProject)
<section class="bg-abyss border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-10 grid gap-4 sm:grid-cols-2">
        @if($prevProject)
            <a href="{{ route('projects.detail', $prevProject->slug) }}" class="card-soft card-hover p-5">
                <small class="text-xs font-bold text-muted uppercase tracking-wider">Projek sebelumnya</small>
                <strong class="block text-white mt-1">{{ $prevProject->name }}</strong>
            </a>
        @else
            <span></span>
        @endif
        @if($nextProject)
            <a href="{{ route('projects.detail', $nextProject->slug) }}" class="card-soft card-hover p-5 sm:text-right">
                <small class="text-xs font-bold text-muted uppercase tracking-wider">Projek berikutnya</small>
                <strong class="block text-white mt-1">{{ $nextProject->name }}</strong>
            </a>
        @endif
    </div>
</section>
@endif
@endsection
