@props(['title', 'breadcrumb' => null])
<section class="bg-night border-b border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-6 md:py-8">
        <h1 class="text-white font-extrabold text-2xl md:text-3xl">{{ $title }}</h1>
        <nav aria-label="Breadcrumb" class="mt-2 text-sm text-muted">
            <a href="{{ route('home') }}" class="hover:text-white font-semibold text-glow">Home</a>
            <span class="mx-1.5" aria-hidden="true">/</span>
            <span aria-current="page" class="text-body">{{ $breadcrumb ?? $title }}</span>
        </nav>
    </div>
</section>
