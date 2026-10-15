@extends('admin.layouts.app')
@section('title', $section->exists ? 'Edit Tentang' : 'Tambah Tentang')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Tentang', 'url' => route('admin.about-sections.index')], ['label' => $section->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$section->exists ? 'Edit Tentang' : 'Tambah Tentang'">
        <form action="{{ $section->exists ? route('admin.about-sections.update', $section) : route('admin.about-sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @if($section->exists) @method('PUT') @endif
            <x-admin.input name="title" label="Judul" :value="$section->title" required />
            <x-admin.input name="subtitle" label="Subtitle" :value="$section->subtitle" />
            <x-admin.summernote name="description" label="Deskripsi" :value="$section->description" :height="200" />
            <x-admin.summernote name="vision" label="Visi" :value="$section->vision" :height="140" />
            <x-admin.textarea name="mission" label="Misi" :value="$section->mission" :rows="4" help="Satu baris satu poin." />
            <x-admin.image-upload name="image" label="Gambar" :existing="$section->image" />
            <x-admin.switch name="is_active" label="Aktif" :checked="old('is_active', $section->is_active ?? true)" />
            <x-admin.form-actions :cancelUrl="route('admin.about-sections.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
