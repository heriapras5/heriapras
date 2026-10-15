@props([
    'label' => null,
    'value' => null,
    'tone' => 'green',
    'icon' => null,
    'hint' => null,
    'delta' => null,
    'deltaPositive' => true,
    'footer' => null,
    'labelId' => null,
    'valueId' => null,
    'hintId' => null,
    'footerId' => null,
    'deltaId' => null,
    'href' => null,
    // Bind ekspresi Alpine: bila diisi, nilai dirender dinamis (x-text/:class),
    // nilai statis di atas tetap dipakai sebagai render awal.
    'valueBind' => null,
    'deltaBind' => null,
    'footerBind' => null,
    'hintBind' => null,
    // Sparkline: ekspresi Alpine yang mengembalikan array angka; digambar
    // sebagai garis kecil di dasar kartu (pola juneo-phone).
    'sparkBind' => null,
])

@php
$toneMap = [
    'blue'   => ['box' => 'from-blue-50 to-blue-100',    'icon' => 'text-blue-600',    'strip' => 'bg-blue-600',    'line' => 'text-blue-500'],
    'amber'  => ['box' => 'from-amber-50 to-amber-100',  'icon' => 'text-amber-600',   'strip' => 'bg-amber-500',   'line' => 'text-amber-500'],
    'green'  => ['box' => 'from-emerald-50 to-emerald-100', 'icon' => 'text-emerald-600', 'strip' => 'bg-emerald-600', 'line' => 'text-emerald-500'],
    'violet' => ['box' => 'from-violet-50 to-violet-100', 'icon' => 'text-violet-600', 'strip' => 'bg-violet-600', 'line' => 'text-violet-500'],
    'teal'   => ['box' => 'from-teal-50 to-teal-100',    'icon' => 'text-teal-600',    'strip' => 'bg-teal-600',    'line' => 'text-teal-500'],
    'rose'   => ['box' => 'from-rose-50 to-rose-100',    'icon' => 'text-rose-600',    'strip' => 'bg-rose-600',    'line' => 'text-rose-500'],
    'gold'   => ['box' => 'from-blue-50 to-blue-100',    'icon' => 'text-blue-600',    'strip' => 'bg-blue-600',    'line' => 'text-blue-500'],
];
$t = $toneMap[$tone] ?? $toneMap['green'];
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group block bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 hover:border-gray-300']) }}>
@else
<div {{ $attributes->merge(['class' => 'group bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 hover:border-gray-300']) }}>
@endif
    <div class="p-4 sm:p-5 flex-1 flex flex-col">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium truncate" @if($labelId)id="{{ $labelId }}"@endif>{{ $label }}</p>
                @if(($hint !== null && $hint !== '') || $hintBind)
                <p class="text-[10px] text-gray-400 italic mt-1 truncate" @if($hintId)id="{{ $hintId }}"@endif @if($hintBind)x-text="{{ $hintBind }}"@endif>{{ $hint }}</p>
                @endif
            </div>
            @if($icon)
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $t['box'] }} flex items-center justify-center shadow-md shrink-0 transition-transform duration-200 group-hover:scale-110">
                <span class="{{ $t['icon'] }} [&>svg]:w-[18px] [&>svg]:h-[18px]">{!! $icon !!}</span>
            </div>
            @endif
        </div>
        <p class="stat-value-text text-2xl lg:text-3xl font-bold text-gray-900 tabular-nums leading-none font-inter transition-all duration-300" @if($valueId)id="{{ $valueId }}"@endif @if($valueBind)x-text="{{ $valueBind }}"@endif>{{ $value }}</p>
        @if($deltaBind)
        <div class="mt-2 text-xs font-semibold flex items-center gap-1 font-inter" :class="{{ $deltaBind }} >= 0 ? 'text-emerald-600' : 'text-red-500'">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path :d="{{ $deltaBind }} >= 0 ? 'm7 17 10-10' : 'm7 7 10 10'"/><path :d="{{ $deltaBind }} >= 0 ? 'M9 7h8v8' : 'M9 17h8V9'"/></svg>
            <span x-text="Math.abs({{ $deltaBind }}) + '%'"></span> vs periode lalu
        </div>
        @elseif($delta !== null)
        <div @if($deltaId)id="{{ $deltaId }}"@endif @class(['mt-2 text-xs font-semibold flex items-center gap-1 font-inter', $deltaPositive ? 'text-emerald-600' : 'text-red-500'])>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $deltaPositive ? 'm7 17 10-10' : 'm7 7 10 10' }}"/><path d="{{ $deltaPositive ? 'M9 7h8v8' : 'M9 17h8V9' }}"/></svg>
            {{ $delta }}% vs periode lalu
        </div>
        @endif
        @if($footer || $footerBind)
        <div class="mt-2 text-xs text-gray-500" @if($footerId)id="{{ $footerId }}"@endif @if($footerBind)x-text="{{ $footerBind }}"@endif>{{ $footer }}</div>
        @endif
        @if($sparkBind)
        <div class="mt-auto pt-3 hidden sm:block {{ $t['line'] }}">
            <svg class="w-full h-8" viewBox="0 0 200 30" preserveAspectRatio="none" aria-hidden="true">
                <polygon :points="sparkFill({{ $sparkBind }})" fill="currentColor" opacity="0.08"></polygon>
                <polyline :points="sparkPoints({{ $sparkBind }})" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></polyline>
            </svg>
        </div>
        @endif
    </div>
    <div class="h-1.5 rounded-b-xl {{ $t['strip'] }}"></div>
@if($href)
</a>
@else
</div>
@endif
