@props([
    'name' => 'gallery',
    'label' => 'Thumbnail',
    'existing' => [],
    'hint' => 'Bisa pilih beberapa gambar sekaligus. Format: JPG, PNG, WEBP.',
])

@php
    $existing = collect($existing ?? [])
        ->values()
        ->filter(fn ($p) => is_string($p) && trim($p) !== '')
        ->map(fn ($p) => ['path' => $p, 'url' => asset($p)])
        ->values()
        ->all();
@endphp

<div x-data="{
    existing: {{ Js::from($existing) }},
    removedExisting: [],
    previews: [],
    addFiles(e) {
        const files = Array.from(e.target.files || []);
        files.forEach(f => {
            if (f.type.startsWith('image/')) {
                this.previews.push({ file: f, url: URL.createObjectURL(f) });
            }
        });
        this.rebuildInput();
    },
    removePreview(i) {
        this.previews.splice(i, 1);
        this.rebuildInput();
    },
    removeExisting(img) {
        this.existing = this.existing.filter(x => x.path !== img);
        this.removedExisting.push(img);
    },
    rebuildInput() {
        const dt = new DataTransfer();
        this.previews.forEach(p => dt.items.add(p.file));
        this.$refs.fileInput.files = dt.files;
    },
}">
    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
    <button type="button" @click="$refs.fileInput.click()" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Upload Gambar
    </button>
    @if($hint)
    <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
    @endif
    <input type="file" x-ref="fileInput" name="{{ $name }}[]" multiple accept="image/*" class="hidden" @change="addFiles($event)">

    <div class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-3" x-show="existing.length > 0 || previews.length > 0">
        <template x-for="(img, i) in existing" :key="'e' + img.path">
            <div class="relative">
                <img :src="img.url" alt="" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                <button type="button" @click="removeExisting(img.path)" title="Hapus thumbnail"
                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
            </div>
        </template>
        <template x-for="(p, i) in previews" :key="'p' + i">
            <div class="relative">
                <img :src="p.url" alt="" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                <button type="button" @click="removePreview(i)" title="Hapus"
                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
            </div>
        </template>
    </div>

    <template x-for="(img, i) in removedExisting" :key="'r' + i">
        <input type="hidden" name="remove_{{ $name }}[]" :value="img">
    </template>
    @error($name)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
