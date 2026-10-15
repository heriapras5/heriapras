@if(count($stats))
@php
    // Controller mengirim nama class Bootstrap Icons (bi-*); CSS vendor sudah
    // dilepas sehingga dipetakan ke SVG inline setara. Markup asing tetap
    // difilter lewat safeIcon().
    $statSvgs = [
        'bi-journal-code' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v18z"/><path d="M14 2v5h5"/><path d="m9 18 1.5-1.5M14 17l-4.5-4.5M11 7l3 3"/></svg>',
        'bi-collection' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/></svg>',
        'bi-lightbulb' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4.6 12.3c.7.6 1.1 1.4 1.3 2.2H15.3c.2-.8.6-1.6 1.3-2.2A7 7 0 0 0 12 2z"/></svg>',
    ];
@endphp
<section id="stats" aria-label="Statistik ringkas">
    <div class="max-w-6xl mx-auto px-5 pb-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($stats as $stat)
                @php $statIcon = $statSvgs[$stat['icon'] ?? ''] ?? safeIcon($stat['icon'] ?? ''); @endphp
                <div class="reveal card-soft px-6 py-5 flex items-center gap-4">
                    <span class="w-11 h-11 rounded-full bg-primary-soft text-primary inline-flex items-center justify-center shrink-0" aria-hidden="true">{!! $statIcon ?: '<span class="font-extrabold">•</span>' !!}</span>
                    <span>
                        <span class="block text-heading font-extrabold text-2xl leading-none" data-count-to="{{ $stat['value'] }}">{{ $stat['value'] }}</span>
                        <span class="block mt-1 text-sm"><strong class="text-heading">{{ $stat['label'] }}</strong> <span class="text-muted">{{ $stat['sub'] }}</span></span>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
