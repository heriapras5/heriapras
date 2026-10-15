@extends('admin.layouts.app')
@section('title', $heroSection->exists ? 'Edit Slide Hero' : 'Tambah Slide Hero')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Hero / Slider', 'url' => route('admin.hero-sections.index')], ['label' => $heroSection->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$heroSection->exists ? 'Edit Slide Hero' : 'Tambah Slide Hero'" :description="$heroSection->exists ? 'Ubah data slide hero.' : 'Buat slide hero baru.'">
        <form action="{{ $heroSection->exists ? route('admin.hero-sections.update', $heroSection) : route('admin.hero-sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @if($heroSection->exists) @method('PUT') @endif
            <x-admin.input name="title" label="Judul" :value="$heroSection->title" required />
            <x-admin.input name="subtitle" label="Subjudul" :value="$heroSection->subtitle" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="button_text" label="Teks Tombol" :value="$heroSection->button_text" />
                <x-admin.input name="button_url" label="URL Tombol" :value="$heroSection->button_url" placeholder="https://wa.me/..." />
            </div>
            <div x-data="{ previews: [], removeImage: false }">
                <label class="form-label">Gambar Slide {{ !$heroSection->exists ? '(bisa upload beberapa)' : '' }}</label>
                <p class="form-hint mb-2">Upload gambar untuk ditampilkan di dalam monitor pada hero section.</p>
                <input type="file" name="{{ $heroSection->exists ? 'image' : 'images[]' }}" {{ $heroSection->exists ? '' : 'multiple' }} accept="image/jpeg,image/png,image/jpg,image/webp"
                       @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f)); removeImage = false"
                       class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="form-hint mt-1">Format: PNG, JPG, WEBP. Maks 2MB per gambar.</p>
                <div x-show="previews.length" class="mt-3 flex gap-3 flex-wrap">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="relative inline-block">
                            <img :src="src" class="h-28 w-44 rounded-xl shadow-sm border border-gray-100 object-cover">
                            <button @click="previews = previews.filter((_, idx) => idx !== i)" type="button"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                        </div>
                    </template>
                </div>
                @if($heroSection->image)
                <div x-show="!previews.length && !removeImage" class="mt-3 relative inline-block">
                    <img src="{{ asset($heroSection->image) }}" class="h-28 w-44 rounded-xl shadow-sm border border-gray-100 object-cover">
                    <label class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs cursor-pointer hover:bg-red-600 shadow-sm">
                        <input type="checkbox" name="remove_image" value="1" @change="removeImage = $event.target.checked" class="hidden">
                        &times;
                    </label>
                </div>
                <div x-show="removeImage && !previews.length" class="mt-3 text-xs text-red-500 italic">Gambar akan dihapus saat disimpan.</div>
                @endif
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @error('images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$heroSection->sort_order ?? 0" />
                <x-admin.select name="is_active" label="Status" :options="['1' => 'Aktif', '0' => 'Nonaktif']" :selected="old('is_active', (string) ($heroSection->is_active ?? 1))" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.hero-sections.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
