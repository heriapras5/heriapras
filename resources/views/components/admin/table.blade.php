@props([
    'head' => null,
    'footer' => null,
    'scrollable' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm']) }}>
    <div @class(['overflow-auto wa-table-scroll max-h-[calc(100vh-280px)]' => $scrollable, 'overflow-x-auto' => !$scrollable])>
        <table class="admin-table-enhanced w-full text-sm">
            @isset($head)
            <thead class="sticky top-0 z-20 bg-gray-50/95 backdrop-blur shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]">
                {{ $head }}
            </thead>
            @endisset
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if($footer)
    <div class="admin-table-footer">{{ $footer }}</div>
    @endif
</div>
