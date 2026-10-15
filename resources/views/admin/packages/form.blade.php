@extends('admin.layouts.app')
@section('title', $package->exists ? 'Edit Paket' : 'Tambah Paket')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Paket', 'url' => route('admin.packages.index')], ['label' => $package->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$package->exists ? 'Edit Paket' : 'Tambah Paket'" :description="$package->exists ? 'Ubah data paket.' : 'Buat paket baru.'">
        <form action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}" method="POST" class="space-y-5">
            @csrf @if($package->exists) @method('PUT') @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="name" label="Nama Paket" :value="$package->name" placeholder="Paket Start" required />
                <x-admin.input name="price" label="Harga" :value="$package->price" placeholder="Rp1.249.000" help="Teks bebas, cth: Rp1.499.000 atau Mulai Rp5.000.000." required />
                <x-admin.input name="old_price" label="Harga Coret" :value="$package->old_price" placeholder="Rp1.499.000" help="Isi untuk tampil promo: harga coret + chip PROMO. Kosongkan untuk tanpa promo." />
            </div>
            <x-admin.input name="tagline" label="Tagline" :value="$package->tagline" placeholder="Cocok untuk bisnis yang baru mulai online." />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="badge" label="Badge" :value="$package->badge" placeholder="Terlaris" help="Kosongkan untuk tanpa badge. Paket ber-badge tampil menonjol." />
                <x-admin.input name="renewal" label="Perpanjangan" :value="$package->renewal" placeholder="Rp1.000.000/tahun" help="Kosongkan untuk menyembunyikan baris perpanjangan." />
            </div>
            <div x-data="{
                facilities: [],
                init() {
                    @if($package->exists && $package->facilities)
                        this.facilities = @js($package->facilities);
                    @elseif(old('facilities'))
                        this.facilities = @js(old('facilities'));
                    @endif
                },
                newFacility: '',
                addFacility() { let t = this.newFacility.trim(); if (t) { this.facilities.push(t); this.newFacility = ''; } },
                removeFacility(i) { this.facilities.splice(i, 1); }
            }">
                <label class="form-label">Fasilitas</label>
                <p class="form-hint mb-2">Daftar fasilitas yang ditampilkan di kartu paket.</p>
                <template x-for="(f, i) in facilities" :key="i">
                    <div class="flex items-center gap-2 mb-2 px-3 py-2 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" class="shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                        <span class="flex-1 text-[13px] text-gray-900" x-text="f"></span>
                        <button @click="removeFacility(i)" type="button" class="text-red-400 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                </template>
                <div class="flex gap-2">
                    <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility()" placeholder="Tambah fasilitas..." class="admin-input flex-1">
                    <button @click="addFacility()" type="button" class="btn btn-primary btn-sm shrink-0">Tambah</button>
                </div>
                <template x-for="(f, i) in facilities" :key="'hidden-' + i">
                    <input type="hidden" name="facilities[]" :value="f">
                </template>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$package->sort_order ?? 0" />
                <x-admin.select name="status" label="Status" :options="['1' => 'Aktif', '0' => 'Nonaktif']" :selected="old('status', (string) ($package->status ?? 1))" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.packages.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
