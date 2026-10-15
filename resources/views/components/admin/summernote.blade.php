@props([
    'name' => null,
    'label' => null,
    'value' => null,
    'required' => false,
    'help' => null,
    'height' => 260,
    'wrapperClass' => '',
])

{{-- Tanpa overflow-hidden: dropdown toolbar Summernote dirender di dalam
    .note-editor, jadi overflow hidden di sini akan memotong menunya. --}}
<div class="block w-full min-w-0 max-w-full space-y-1.5 {{ $wrapperClass }}">
    @if($label)
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)<span class="text-red-400">*</span>@endif
    </label>
    @endif
    {{-- required hanya mengendalikan tanda bintang di label. Atribut required
        TIDAK dipasang pada textarea karena summernote menyembunyikan textarea
        asli (display:none); required pada kontrol tersembunyi membuat browser
        memblokir submit tanpa pesan yang terlihat. Validasi ditangani server. --}}
    <textarea name="{{ $name }}" id="{{ $name }}" class="summernote" @if($height !== 260) data-height="{{ $height }}" @endif
              >{{ old($name, $value) }}</textarea>
    @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    @if($help)<p class="text-xs text-gray-400 mt-1">{{ $help }}</p>@endif
</div>
