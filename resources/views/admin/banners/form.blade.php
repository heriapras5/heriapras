@extends('admin.layouts.app')
@section('title', $banner->exists ? 'Edit Banner' : 'Tambah Banner')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Banner', 'url' => route('admin.banners.index')], ['label' => $banner->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$banner->exists ? 'Edit Banner' : 'Tambah Banner'">
        <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @if($banner->exists) @method('PUT') @endif
            <x-admin.input name="title" label="Judul" :value="$banner->title" required />
            <x-admin.input name="subtitle" label="Subtitle" :value="$banner->subtitle" />
            <x-admin.image-upload name="image" label="Gambar" :existing="$banner->image" hint="Format: JPG, PNG, WEBP. Maks 2MB." />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="link" label="Link" :value="$banner->link" />
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$banner->sort_order ?? 0" />
            </div>
            <x-admin.switch name="is_active" label="Aktif" :checked="old('is_active', $banner->is_active ?? true)" />
            <x-admin.form-actions :cancelUrl="route('admin.banners.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
