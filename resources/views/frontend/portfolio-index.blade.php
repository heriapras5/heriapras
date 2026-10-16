@extends('layouts.frontend')
@section('title', setting('tab_portfolio') ?: 'Portfolio - Heri A Pras')
@section('meta_description', setting('landing_portfolio_subtitle') ?: 'Kumpulan proyek website dan aplikasi yang telah saya bangun.')
@section('body_class', 'portfolio-index-page')

@section('content')
@include('partials.frontend.page-title', ['title' => setting('landing_portfolio_title') ?: 'Portfolio', 'breadcrumb' => 'Portfolio'])

<section class="bg-abyss">
    <div class="max-w-6xl mx-auto px-5 py-12 md:py-16">
        <p class="text-center text-[15px] text-muted max-w-xl mx-auto">{{ setting('landing_portfolio_subtitle') ?: 'Berikut adalah beberapa proyek website dan aplikasi yang telah saya bangun.' }}</p>
        @if($categories->count())
        <div class="mt-6 flex flex-nowrap justify-start md:justify-center gap-2 overflow-x-auto tabs-scroll max-w-full px-1 py-1" aria-label="Filter kategori portfolio">
            <a href="{{ route('portfolio.index') }}" class="portfolio-tab shrink-0 whitespace-nowrap {{ $activeCategory ? '' : 'portfolio-tab-active' }}">Semua</a>
            @foreach($categories as $category)
            <a href="{{ route('portfolio.index', ['kategori' => $category->slug]) }}" class="portfolio-tab shrink-0 whitespace-nowrap {{ $activeCategory && $activeCategory->id === $category->id ? 'portfolio-tab-active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </div>
        @endif
        @if($projects->count())
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($projects as $project)
                @include('partials.frontend.project-card', ['project' => $project, 'isClone' => false])
            @endforeach
        </div>
        @else
        <div class="mt-8 text-center py-10">
            <p class="text-sm text-muted">Belum ada project pada kategori ini.</p>
            <a href="{{ route('portfolio.index') }}" class="btn-pill btn-outline mt-4 inline-block">Lihat Semua</a>
        </div>
        @endif
    </div>
</section>
@endsection
