@extends('admin.layouts.app')
@section('title', 'My Journey')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'My Journey']]" />
<div class="max-w-6xl mx-auto space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#1e293b]">My Journey</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pendidikan, pembelajaran, dan pengalaman yang ditampilkan di portofolio.</p>
        </div>
        <x-admin.add-button route="admin.timeline.create" label="Tambah Timeline" />
    </div>
    <x-admin.table>
        <x-slot:head>
            <x-admin.th>Urutan</x-admin.th>
            <x-admin.th>Jenis</x-admin.th>
            <x-admin.th>Judul</x-admin.th>
            <x-admin.th>Organisasi</x-admin.th>
            <x-admin.th>Periode</x-admin.th>
            <x-admin.th>Status</x-admin.th>
            <x-admin.th>Aksi</x-admin.th>
        </x-slot:head>
        @forelse($items as $item)
        <tr>
            <x-admin.td>{{ $item->sort_order }}</x-admin.td>
            <x-admin.td><span class="text-xs font-semibold text-blue-600">{{ strtoupper($item->type) }}</span></x-admin.td>
            <x-admin.td>{{ $item->title }}</x-admin.td>
            <x-admin.td>{{ $item->organization ?: '-' }}</x-admin.td>
            <x-admin.td>{{ $item->period ?: '-' }}</x-admin.td>
            <x-admin.td><x-admin.status-toggle resource="portfolio_timeline_items" :id="$item->id" :checked="$item->is_active" /></x-admin.td>
            <x-admin.td><x-admin.actions edit="admin.timeline.edit" destroy="admin.timeline.destroy" :params="[$item]" /></x-admin.td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-gray-400">Belum ada timeline.</td></tr>
        @endforelse
    </x-admin.table>
    <div>{{ $items->links() }}</div>
</div>
@endsection
