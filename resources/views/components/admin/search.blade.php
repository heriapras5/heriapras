@props([
    'route' => null,
    'placeholder' => 'Cari...',
    'button' => 'Cari',
    'reset' => true,
    'filterNames' => ['search'],
])

<div class="admin-search bg-white rounded-xl border border-gray-200 shadow-sm p-3.5 sm:p-4 mb-4">
    <form method="GET" action="{{ $route ? route($route) : request()->url() }}">
        <div class="flex flex-wrap items-center gap-2.5">
            <div class="relative w-full sm:flex-1 sm:min-w-[200px] sm:max-w-[320px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       @if($placeholder)placeholder="{{ $placeholder }}" @endif
                       class="admin-input admin-input-icon">
            </div>
            {{ $filters ?? '' }}
            <button type="submit" class="btn btn-primary btn-sm">{{ $button }}</button>
            @if($reset && collect($filterNames)->contains(fn($n) => request()->filled($n)))
            <a href="{{ $route ? route($route) : request()->url() }}" class="btn btn-ghost btn-sm">Reset</a>
            @endif
            <div class="ml-auto flex items-center gap-2">
                {{ $actions ?? '' }}
            </div>
        </div>
    </form>
</div>
