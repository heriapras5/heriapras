@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
    'disabled' => false,
    'disabledOptions' => [],
])

<div class="min-w-0 w-full space-y-1.5">
    @if($label)
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)<span class="text-red-400">*</span>@endif
    </label>
    @endif
    <select name="{{ $name }}" id="{{ $name }}"
            @if($disabled)disabled @endif
            {{ $attributes->merge(['class' => 'admin-input bg-white' . ($disabled ? ' opacity-60 cursor-not-allowed' : '')]) }}>
        @if($placeholder)
            <option value="" @selected(!old($name) && !$selected)>{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @selected((string) old($name, $selected) === (string) $value) @disabled(in_array($value, $disabledOptions))>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    @if($help)<p class="text-xs text-gray-400 mt-1">{{ $help }}</p>@endif
</div>
