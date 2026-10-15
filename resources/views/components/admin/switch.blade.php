@props([
    'name' => 'status',
    'label' => 'Aktif',
    'checked' => false,
    'help' => null,
])

<div class="flex items-center justify-between gap-3 py-1">
    <div class="min-w-0">
        <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
        @if($help)<p class="text-xs text-gray-400 mt-0.5">{{ $help }}</p>@endif
    </div>
    <label class="relative inline-flex items-center cursor-pointer shrink-0">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) old($name, $checked)) {{ $attributes->merge(['class' => 'sr-only peer']) }}>
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-600/25 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
    </label>
</div>
