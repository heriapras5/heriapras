@props([
    'color' => 'gray',
    'soft' => false,
])

@php
    $solid = [
        'green' => 'bg-emerald-600 text-white',
        'red' => 'bg-red-600 text-white',
        'amber' => 'bg-amber-500 text-white',
        'blue' => 'bg-blue-600 text-white',
        'gray' => 'bg-gray-600 text-white',
        'gold' => 'bg-blue-600 text-white',
    ];
    $softTones = [
        'green' => 'admin-badge-soft-green',
        'red' => 'admin-badge-soft-rose',
        'rose' => 'admin-badge-soft-rose',
        'amber' => 'admin-badge-soft-amber',
        'orange' => 'admin-badge-soft-orange',
        'blue' => 'admin-badge-soft-blue',
        'violet' => 'admin-badge-soft-violet',
        'teal' => 'admin-badge-soft-teal',
        'indigo' => 'admin-badge-soft-indigo',
        'gray' => 'admin-badge-soft-slate',
        'slate' => 'admin-badge-soft-slate',
        'gold' => 'admin-badge-soft-amber',
    ];
    $classes = $soft
        ? ($softTones[$color] ?? $softTones['gray'])
        : ($solid[$color] ?? $solid['gray']);
    $pad = $soft ? 'px-2.5 py-0.5' : 'px-2.5 py-1';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 ' . $pad . ' rounded-full text-xs font-semibold whitespace-nowrap ' . $classes]) }}>
    {{ $slot }}
</span>
