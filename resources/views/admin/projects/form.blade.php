@extends('admin.layouts.app')
@section('title', $project->exists ? 'Edit Proyek' : 'Tambah Proyek')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proyek', 'url' => route('admin.projects.index')], ['label' => $project->exists ? 'Edit' : 'Tambah']]" />
<form action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
    @csrf @if($project->exists) @method('PUT') @endif
    <div class="max-w-4xl mx-auto">
        <x-admin.form-card :title="$project->exists ? 'Edit Proyek' : 'Tambah Proyek'" :description="$project->exists ? 'Ubah data proyek.' : 'Buat proyek baru.'">
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="name" label="Judul Proyek" :value="$project->name" required />
                    <div>
                        <label class="form-label">Kategori <span class="text-xs text-gray-400 font-normal">(bisa pilih lebih dari satu)</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $c)
                            <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50/60 text-blue-700 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors text-xs font-medium">
                                <input type="checkbox" name="categories[]" value="{{ $c->id }}"
                                       {{ in_array($c->id, old('categories', $project->exists ? $project->categories->pluck('id')->all() : [])) ? 'checked' : '' }}
                                       class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                {{ $c->name }}
                            </label>
                            @endforeach
                        </div>
                        @error('categories')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <x-admin.input name="url" label="Live URL" type="url" :value="$project->url" placeholder="https://..." />
                    <x-admin.input name="client" label="Project Context" :value="$project->client" placeholder="Nama brand atau konteks project" />
                    <x-admin.input name="year" label="Tahun" :value="$project->year" placeholder="2026" />
                    <x-admin.input name="duration" label="Durasi" :value="$project->duration" placeholder="2 bulan" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.input name="sort_order" label="Urutan" type="number" :value="$project->sort_order ?? 0" />
                    <x-admin.textarea name="result_text" label="Project outcome" :value="$project->result_text" :rows="3" placeholder="What changed after this project was built?" />
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border cursor-pointer transition-colors {{ (old('status', $project->status ?? true) ? 'border-blue-500 bg-blue-50/60' : 'border-gray-200') }}">
                            <input type="radio" name="status" value="1" {{ old('status', $project->status ?? true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-[13px] font-medium">Aktif (ditampilkan)</span>
                        </label>
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border cursor-pointer transition-colors {{ (!old('status', $project->status ?? true) ? 'border-red-300 bg-red-50/60' : 'border-gray-200') }}">
                            <input type="radio" name="status" value="0" {{ !old('status', $project->status ?? true) ? 'checked' : '' }} class="w-4 h-4 text-red-500 focus:ring-red-500">
                            <span class="text-[13px] font-medium">Nonaktif (disembunyikan)</span>
                        </label>
                    </div>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <x-admin.summernote name="description" label="Deskripsi" :value="$project->description" :height="360" :required="true" help="Tulis deskripsi lengkap proyek termasuk fitur-fiturnya di sini." />

                {{-- Fitur Utama --}}
                <div x-data="{
                    rows: @js($project->exists ? $project->features->map(fn($f) => ['feature' => $f->feature, 'description' => $f->description])->values() : []),
                    add() { this.rows.push({ feature: '', description: '' }); },
                    remove(i) { this.rows.splice(i, 1); }
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="form-label !mb-0">Fitur Utama</label>
                        <button type="button" @click="add()" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Fitur
                        </button>
                    </div>
                    <p class="form-hint mb-3">Fitur yang ditampilkan di halaman detail proyek (blok "Fitur Utama").</p>
                    <template x-for="(row, i) in rows" :key="i">
                        <div class="mb-3 p-4 bg-gray-50/70 rounded-xl border border-gray-100">
                            <div class="flex items-start gap-2">
                                <div class="flex-1 space-y-2">
                                    <input type="text" x-model="row.feature" :name="'features[' + i + '][feature]'"
                                           placeholder="Nama fitur (mis. Dashboard Real-time)"
                                           class="admin-input">
                                    <input type="text" x-model="row.description" :name="'features[' + i + '][description]'"
                                           placeholder="Deskripsi singkat (opsional)"
                                           class="admin-input">
                                </div>
                                <button @click="remove(i)" type="button" class="text-red-400 hover:text-red-600 mt-1 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="!rows.length" class="text-[13px] text-gray-400 bg-gray-50 rounded-xl px-4 py-6 text-center border border-dashed border-gray-200">
                        Belum ada fitur. Klik "Tambah Fitur" untuk menambahkan.
                    </div>
                </div>

                {{-- Teknologi --}}
                <div x-data="{
                    rows: @js($project->exists ? $project->technologies->map(fn($t) => ['name' => $t->name])->values() : []),
                    add() { this.rows.push({ name: '' }); },
                    remove(i) { this.rows.splice(i, 1); }
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="form-label !mb-0">Teknologi yang Digunakan</label>
                        <button type="button" @click="add()" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Teknologi
                        </button>
                    </div>
                    <p class="form-hint mb-3">Contoh: Laravel, Vue.js, MySQL, dsb.</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(row, i) in rows" :key="i">
                            <div class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50/70 rounded-lg border border-blue-100">
                                <input type="text" x-model="row.name" :name="'technologies[' + i + '][name]'"
                                       placeholder="Nama teknologi"
                                       class="w-32 bg-transparent text-[13px] outline-none placeholder:text-gray-400">
                                <button @click="remove(i)" type="button" class="text-red-400 hover:text-red-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="add()" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-dashed border-gray-300 text-gray-400 hover:text-blue-600 hover:border-blue-300 transition-colors" aria-label="Tambah teknologi">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Gambar Utama (Cover) --}}
                <div x-data="{ mainPreview: null }">
                    <label class="form-label">Gambar Utama <span class="text-xs text-gray-400 font-normal">(cover/hero)</span></label>
                    <input type="file" name="main_image" accept="image/jpeg,image/png,image/jpg,image/webp"
                           @change="mainPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Format: PNG, JPG, WEBP. Maks 2MB. Gambar ini akan menjadi cover/hero proyek.</p>
                    @error('main_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <div x-show="mainPreview" class="mt-3 relative inline-block">
                        <img :src="mainPreview" class="h-28 w-44 rounded-xl shadow-sm border border-gray-100 object-cover">
                        <button @click="mainPreview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" type="button"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                    </div>
                    @php $_cover = $project->exists ? $project->images->firstWhere('type', 'cover') : null; @endphp
                    @if($_cover)
                    <div x-show="!mainPreview" class="mt-3 relative inline-block">
                        <img src="{{ asset($_cover->image) }}" class="h-28 w-44 rounded-xl shadow-sm border border-gray-100 object-cover">
                        <label class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs cursor-pointer hover:bg-red-600 shadow-sm">
                            <input type="checkbox" name="deleted_images[]" value="{{ $_cover->id }}" class="hidden">
                            &times;
                        </label>
                    </div>
                    @endif
                </div>

                {{-- Gambar Thumbnail (Galeri) --}}
                <div x-data="{ galleryFiles: [], galleryPreviews: [] }">
                    <label class="form-label">Gambar Thumbnail <span class="text-xs text-gray-400 font-normal">(galeri tambahan)</span></label>
                    <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp"
                           x-ref="galleryInput"
                           @change="galleryFiles = Array.from($event.target.files); galleryPreviews = galleryFiles.map(f => URL.createObjectURL(f))"
                           class="admin-input file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="form-hint mt-1">Upload beberapa gambar sekaligus untuk galeri proyek. Maks 2MB per gambar. Tanpa centang replace, gambar baru akan ditambahkan ke galeri lama.</p>
                    @error('gallery_images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <label class="flex items-center gap-2 mt-2 text-[13px] text-gray-600 cursor-pointer">
                        <input type="checkbox" name="replace_gallery" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5">
                        Ganti semua gambar lama dengan upload baru (replace)
                    </label>
                    <div class="flex gap-3 mt-3 flex-wrap" x-show="galleryPreviews.length">
                        <template x-for="(src, i) in galleryPreviews" :key="i">
                            <div class="relative w-28 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm group">
                                <img :src="src" class="w-full h-full object-cover">
                                <button @click="galleryFiles.splice(i, 1); galleryPreviews.splice(i, 1); const dt = new DataTransfer(); galleryFiles.forEach(f => dt.items.add(f)); $refs.galleryInput.files = dt.files" type="button"
                                        class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm opacity-0 group-hover:opacity-100">&times;</button>
                            </div>
                        </template>
                    </div>
                    @php $_gallery = $project->exists ? $project->images->where('type', 'gallery') : collect(); @endphp
                    @if($_gallery->count())
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-500 mb-2">Gambar saat ini (klik tanda silang untuk menghapus):</p>
                        <div class="flex gap-3 flex-wrap" id="existing-gallery">
                            @foreach($_gallery as $img)
                            <div class="relative w-28 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm" id="gallery-img-{{ $img->id }}">
                                <img src="{{ asset($img->image) }}" class="w-full h-full object-cover">
                                <button type="button" @click="window.hapusGambarGaleri({{ $project->id }}, {{ $img->id }}, 'gallery-img-{{ $img->id }}')" title="Hapus gambar ini"
                                        class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow-sm">&times;</button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <x-admin.form-actions cancelUrl="{{ route('admin.projects.index') }}" submitLabel="Simpan" />
        </x-admin.form-card>
    </div>
</form>
@endsection
