@php
    $base = 'align-middle';
    if (!preg_match('/text-(?:left|center|right|justify)/', (string) $attributes->get('class'))) {
        $base .= ' text-left';
    }
@endphp
<td {{ $attributes->merge(['class' => $base]) }}>{{ $slot }}</td>