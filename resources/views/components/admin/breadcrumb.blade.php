@props([
    'items' => [],
])

<nav class="admin-breadcrumb">
    @foreach($items as $item)
        @if(! $loop->last && ! empty($item['url']))
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            <span>/</span>
        @else
            <span class="current">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
