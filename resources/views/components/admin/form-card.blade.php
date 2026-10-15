@props([
    'title' => null,
    'description' => null,
    'tone' => 'green',
    'actions' => null,
    'icon' => null,
])

@php
    $tones = [
        'green' => 'bg-emerald-50 text-emerald-700',
        'emerald' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue' => 'bg-sky-50 text-sky-700',
        'purple' => 'bg-purple-50 text-purple-700',
        'orange' => 'bg-orange-50 text-orange-700',
        'red' => 'bg-red-50 text-red-700',
    ];
    $tone = $tones[$tone] ?? $tones['green'];
@endphp

{{-- Tanpa overflow-hidden agar dropdown Summernote tidak terpotong.
    Sudut dibulatkan per elemen (header/body) sebagai gantinya. --}}
<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm']) }}>
    @if($title || $actions)
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 rounded-t-xl flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $tone }}">
                {{ $icon ?? '' }}
            </div>
            <div class="min-w-0">
                <h3 class="text-sm font-bold text-gray-900">{{ $title }}</h3>
                @if($description)<p class="text-xs text-gray-500 mt-0.5">{{ $description }}</p>@endif
            </div>
        </div>
        @if($actions)
        <div class="shrink-0 flex items-center gap-2">{{ $actions }}</div>
        @endif
    </div>
    @endif
    <div @class(['p-6', 'rounded-b-xl' => $title || $actions, 'rounded-xl' => !($title || $actions)])>
        {{ $slot }}
    </div>
</div>
