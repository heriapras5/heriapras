@props([
    'title' => null,
    'description' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm']) }}>
    @if($title || $actions)
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-sm font-bold text-gray-900">{{ $title }}</h3>
            @if($description)
            <p class="text-xs text-gray-500 mt-0.5">{{ $description }}</p>
            @endif
        </div>
        @if($actions)
        <div class="shrink-0 flex items-center gap-2">{{ $actions }}</div>
        @endif
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
