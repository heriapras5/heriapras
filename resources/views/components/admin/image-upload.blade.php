@props([
    'name' => 'image',
    'label' => 'Gambar',
    'existing' => null,
    'required' => false,
    'hint' => 'Format: JPG, PNG, WEBP',
    'previewClass' => 'h-32 rounded-xl border border-gray-200',
    'round' => false,
])

@php
    $previewClass = $round ? 'h-24 w-24 rounded-full object-cover border border-gray-200' : $previewClass;
@endphp

<div x-data="{ preview: null, removeImage: false }">
    <label class="form-label">
        {{ $label }}
        @if($required)<span class="text-red-400">*</span>@endif
    </label>
    <button type="button" @click="$refs.imgInput.click()" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Upload Gambar
    </button>
    @if($hint)
    <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
    @endif
    <input type="file" name="{{ $name }}" accept="image/*" x-ref="imgInput"
           @if($required)required @endif
           @change="preview = URL.createObjectURL($event.target.files[0]); removeImage = false"
           class="hidden">
    @if($existing)
    <template x-if="!preview && !removeImage">
        <div class="relative inline-block mt-2">
            <img src="{{ asset($existing) }}" alt="{{ $label }}" class="{{ $previewClass }}">
            <button type="button" @click="confirmRemoveImage().then((r)=>{if(r.isConfirmed){removeImage=true}})" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
        </div>
    </template>
    @endif
    <template x-if="preview">
        <div class="relative inline-block mt-2">
            <img :src="preview" alt="Preview" class="{{ $previewClass }}">
            <button type="button" @click="preview = null; $refs.imgInput.value = ''" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
        </div>
    </template>
    <input type="hidden" name="remove_{{ $name }}" :value="removeImage ? '1' : ''">
    @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
