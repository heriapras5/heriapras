@extends('admin.layouts.app')
@section('title', $faq->exists ? 'Edit FAQ' : 'Tambah FAQ')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'FAQ', 'url' => route('admin.faqs.index')], ['label' => $faq->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-2xl">
    <x-admin.form-card :title="$faq->exists ? 'Edit FAQ' : 'Tambah FAQ'" :description="$faq->exists ? 'Ubah data FAQ.' : 'Buat FAQ baru.'">
        <form action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST" class="space-y-5">
            @csrf @if($faq->exists) @method('PUT') @endif
            <x-admin.input name="question" label="Pertanyaan" :value="$faq->question" required />
            <x-admin.summernote name="answer" label="Jawaban" :value="$faq->answer" :height="200" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="sort_order" label="Urutan" type="number" :value="$faq->sort_order ?? 0" />
                <x-admin.select name="status" label="Status" :options="['1' => 'Aktif', '0' => 'Nonaktif']" :selected="old('status', (string) ($faq->status ?? 1))" />
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.faqs.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
