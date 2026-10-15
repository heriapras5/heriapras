@extends('admin.layouts.app')
@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Kategori Projek', 'url' => route('admin.project-categories.index')], ['label' => $category->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$category->exists ? 'Edit Kategori' : 'Tambah Kategori'" :description="$category->exists ? 'Ubah data kategori.' : 'Buat kategori baru.'">
        <form action="{{ $category->exists ? route('admin.project-categories.update', $category) : route('admin.project-categories.store') }}" method="POST" class="space-y-5">
            @csrf @if($category->exists) @method('PUT') @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="name" label="Nama Kategori" :value="$category->name" required />
                <x-admin.input name="slug" label="Slug" :value="$category->slug" help="Biarkan kosong untuk otomatis." />
            </div>
            <x-admin.summernote name="description" label="Deskripsi" :value="$category->description" :height="180" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$category->sort_order ?? 0" />
                <x-admin.select name="status" label="Status" :options="['1' => 'Aktif', '0' => 'Nonaktif']" :selected="old('status', (string) ($category->status ?? 1))" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.project-categories.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
