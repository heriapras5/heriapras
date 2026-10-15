@props([
    'title' => null,
    'description' => null,
    'tone' => 'green',
    'icon' => null,
    'openKey' => null,
    'defaultOpen' => false,
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

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
    <button type="button"
            @click="open = !open"
            class="w-full flex items-center gap-3.5 px-6 py-4 text-left transition hover:bg-gray-50/60">
        <span class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $tone }}">
            {{ $icon ?? '' }}
        </span>
        <span class="min-w-0 flex-1">
            <span class="block text-sm font-bold text-gray-900">{{ $title }}</span>
            @if($description)
            <span class="block text-xs text-gray-500 mt-0.5">{{ $description }}</span>
            @endif
        </span>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 shrink-0"><path d="m18 15-6-6-6 6"/></svg>
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 shrink-0"><path d="m6 9 6 6 6-6"/></svg>
    </button>
    <div x-show="open" x-collapse class="border-t border-gray-100">
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
