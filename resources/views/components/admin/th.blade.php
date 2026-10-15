@php
    $base = 'align-middle font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap';
    if (!preg_match('/text-(?:left|center|right|justify)/', (string) $attributes->get('class'))) {
        $base .= ' text-left';
    }
@endphp
<th {{ $attributes->merge(['class' => $base]) }}>{{ $slot }}</th>