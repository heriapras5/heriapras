@extends('admin.layouts.app')
@section('title', $service->exists ? 'Edit Keahlian' : 'Tambah Keahlian')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Keahlian', 'url' => route('admin.services.index')], ['label' => $service->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$service->exists ? 'Edit Keahlian' : 'Tambah Keahlian'" :description="$service->exists ? 'Ubah data keahlian.' : 'Buat keahlian baru.'">
        <form action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" class="space-y-5">
            @csrf @if($service->exists) @method('PUT') @endif
            <x-admin.input name="title" label="Nama" :value="$service->title" required />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="type" value="skill">
                <x-admin.select name="group_name" label="Kategori" :options="['Frontend' => 'Frontend', 'Backend' => 'Backend', 'Tools' => 'Tools', 'Database' => 'Database']" :selected="old('group_name', $service->group_name ?? 'Frontend')" />
            </div>
            <p class="form-hint -mt-3">Keahlian tampil di section Keahlian (progress bar).</p>
            @php
                $iconOptions = [
                    'globe' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
                    'monitor' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                    'shopping-bag' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                    'cog' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
                    'phone' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
                    'chart-bar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
                    'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'shield' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                    'lightning' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                    'puzzle' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/><path d="M8 10v4"/><path d="M12 10v4"/><path d="M16 10v4"/></svg>',
                    'academic-cap' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
                    'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
                    'camera' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
                    'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
                    'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                ];
            @endphp
            <div x-data="{ selectedIcon: '{{ old('icon', $service->icon) }}' }">
                <label class="form-label">Ikon <span class="text-red-400">*</span></label>
                <p class="form-hint mb-2">Pilih ikon yang mewakili layanan ini.</p>
                <select name="icon" x-model="selectedIcon" class="admin-input bg-white" required>
                    <option value="">-- Pilih Ikon --</option>
                    @foreach($iconOptions as $name => $svg)
                    <option value="{{ $svg }}" {{ old('icon', $service->icon) == $svg ? 'selected' : '' }}>
                        {{ ucwords(str_replace('-', ' ', $name)) }}
                    </option>
                    @endforeach
                </select>
                <div class="mt-3 flex items-center gap-3">
                    <span class="text-xs text-gray-500">Preview:</span>
                    <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center text-blue-600 [&>svg]:w-5 [&>svg]:h-5" x-html="selectedIcon || ''"></div>
                </div>
                @error('icon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <x-admin.summernote name="description" label="Deskripsi" :value="$service->description" :height="200" />
            <div x-data="{
                features: [],
                init() {
                    @if($service->exists && $service->features)
                        this.features = @js($service->features);
                    @elseif(old('features'))
                        this.features = @js(old('features'));
                    @endif
                },
                newFeature: '',
                addFeature() { let t = this.newFeature.trim(); if (t) { this.features.push(t); this.newFeature = ''; } },
                removeFeature(i) { this.features.splice(i, 1); }
            }">
                <label class="form-label">Fitur Layanan</label>
                <p class="form-hint mb-2">Daftar fitur yang ditampilkan di halaman layanan.</p>
                <template x-for="(f, i) in features" :key="i">
                    <div class="flex items-center gap-2 mb-2 px-3 py-2 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                        <span class="flex-1 text-[13px] text-gray-900" x-text="f"></span>
                        <button @click="removeFeature(i)" type="button" class="text-red-400 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                </template>
                <div class="flex gap-2">
                    <input type="text" x-model="newFeature" @keydown.enter.prevent="addFeature()" placeholder="Tambah fitur..." class="admin-input flex-1">
                    <button @click="addFeature()" type="button" class="btn btn-primary btn-sm shrink-0">Tambah</button>
                </div>
                <input type="hidden" name="features" :value="JSON.stringify(features)">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-admin.input name="percent" label="Persen (%)" type="number" :value="$service->percent ?? ''" help="Khusus skill, 0-100. Kosongkan untuk layanan." />
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$service->sort_order ?? 0" />
                <x-admin.select name="status" label="Status" :options="['1' => 'Aktif', '0' => 'Nonaktif']" :selected="old('status', (string) ($service->status ?? 1))" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.services.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
