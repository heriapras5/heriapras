@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit Timeline' : 'Tambah Timeline')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'My Journey', 'url' => route('admin.timeline.index')], ['label' => $item->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl mx-auto">
    <x-admin.form-card :title="$item->exists ? 'Edit Timeline' : 'Tambah Timeline'">
        <form action="{{ $item->exists ? route('admin.timeline.update', $item) : route('admin.timeline.store') }}" method="POST" class="space-y-5">
            @csrf @if($item->exists) @method('PUT') @endif
            <x-admin.select name="type" label="Jenis" :options="['education' => 'Education', 'experience' => 'Experience', 'learning' => 'Learning']" :selected="old('type', $item->type ?? 'education')" />
            <x-admin.input name="title" label="Judul" :value="$item->title" required />
            <x-admin.input name="organization" label="Organisasi / Institusi" :value="$item->organization" />
            <x-admin.input name="period" label="Periode" :value="$item->period" placeholder="2024 - Sekarang" />
            <x-admin.textarea name="description" label="Deskripsi" :value="$item->description" :rows="5" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$item->sort_order ?? 0" />
                <x-admin.switch name="is_active" label="Aktif" :checked="old('is_active', $item->is_active ?? true)" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.timeline.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
