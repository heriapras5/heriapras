@extends('admin.layouts.app')
@section('title', 'Proyek')
@section('content')
@php
// Peta slug kategori proyek -> tone badge soft (lihat .admin-badge-soft-* di admin.css)
$categoryTones = [
    'company' => 'blue',
    'catalog' => 'amber',
    'umkm' => 'green',
    'sistem' => 'violet',
    'landing' => 'rose',
    'custom' => 'orange',
];
@endphp
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proyek']]" />
<x-admin.section-title title="Daftar Proyek" subtitle="Kelola semua proyek/portofolio website">
    <x-slot:actions><x-admin.add-button route="admin.projects.create" label="Tambah Proyek" /></x-slot:actions>
</x-admin.section-title>
<div class="w-full md:w-fit md:max-w-full">
<form method="GET" action="{{ route('admin.projects.index') }}" class="admin-search bg-white rounded-xl border border-gray-200 shadow-sm p-3.5 sm:p-4 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,20rem)_13rem_auto] items-center gap-2.5">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proyek..."
                   class="admin-input admin-input-icon">
        </div>
        <select name="category_id" class="admin-input w-full cursor-pointer">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-2.5">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request()->filled('search') || request()->filled('category_id'))
            <a href="{{ route('admin.projects.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            @endif
        </div>
    </div>
</form>
</div>
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th class="w-16">Gambar</x-admin.th>
        <x-admin.th>Judul Proyek</x-admin.th>
        <x-admin.th>Kategori</x-admin.th>
        <x-admin.th class="w-16">Tahun</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-28 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($projects as $index => $p)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $projects->firstItem() + $index }}</x-admin.td>
        <x-admin.td>
            @if($p->coverImage())
            <img src="{{ asset($p->coverImage()->image) }}" class="w-14 h-10 object-cover rounded-lg shadow-sm border border-gray-100">
            @else
            <div class="w-14 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            </div>
            @endif
        </x-admin.td>
        <x-admin.td>
            <div class="font-medium">{{ $p->name }}</div>
            @if($p->client)<div class="text-[11px] text-gray-400 font-normal mt-0.5">Klien: {{ $p->client }}</div>@endif
        </x-admin.td>
        <x-admin.td>
            @if($p->categories->count())
            <div class="flex flex-wrap gap-1">
                @foreach($p->categories as $c)
                <x-admin.badge :color="$categoryTones[$c->slug] ?? 'slate'" soft class="text-[11px]">{{ $c->name }}</x-admin.badge>
                @endforeach
            </div>
            @else
            <span class="text-gray-400">-</span>
            @endif
        </x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $p->year ?: '-' }}</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="projects" :id="$p->id" :checked="$p->status" /></x-admin.td>
        <x-admin.td>
            <x-admin.actions edit="admin.projects.edit" destroy="admin.projects.destroy" :params="[$p]" :showUrl="url('/portfolio/' . $p->slug)" showTarget="_blank" />
        </x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="7" message="Belum ada proyek." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $projects->firstItem() ?? 0 }} sampai {{ $projects->lastItem() ?? 0 }} dari {{ $projects->total() }} data</p>
        {{ $projects->appends(['search' => request('search'), 'category_id' => request('category_id')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
