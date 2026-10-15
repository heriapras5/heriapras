@props([
    'title' => null,
    'subtitle' => null,
])

<div class="flex items-center justify-between gap-4 mb-4">
    <h2 class="admin-section-title">
        {{ $title }}
        @if($subtitle)<span class="sub">- {{ $subtitle }}</span>@endif
    </h2>
    @isset($actions)
    <div class="shrink-0 flex items-center gap-2">{{ $actions }}</div>
    @endisset
</div>