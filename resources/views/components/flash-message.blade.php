@props(['type' => 'success', 'message' => ''])

@php
$icons = [
    'success' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
    'error' => '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
    'warning' => '<path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
    'info' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
];
$titles = [
    'success' => 'Berhasil!',
    'error' => 'Gagal!',
    'warning' => 'Perhatian!',
    'info' => 'Informasi',
];
$colors = [
    'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'icon' => 'text-green-600', 'iconBg' => 'bg-green-100'],
    'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'icon' => 'text-red-600', 'iconBg' => 'bg-red-100'],
    'warning' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'icon' => 'text-amber-600', 'iconBg' => 'bg-amber-100'],
    'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'icon' => 'text-blue-600', 'iconBg' => 'bg-blue-100'],
];
$c = $colors[$type] ?? $colors['success'];
@endphp

@if($message)
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-4"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-4"
     class="flex items-center gap-3 {{ $c['bg'] }} border {{ $c['border'] }} rounded-xl shadow-lg p-3.5 mb-3 w-full">
    <div class="w-8 h-8 rounded-full {{ $c['iconBg'] }} flex items-center justify-center shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $c['icon'] }}">{!! $icons[$type] !!}</svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold {{ $c['icon'] }}">{{ $titles[$type] }}</p>
        <p class="text-xs {{ str_replace('600', '700', $c['icon']) }} opacity-80 truncate">{{ $message }}</p>
    </div>
    <button @click="show = false" class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center hover:{{ $c['bg'] }} transition {{ $c['icon'] }}/70 hover:{{ str_replace('text-', 'text-', $c['icon']) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>
@endif
