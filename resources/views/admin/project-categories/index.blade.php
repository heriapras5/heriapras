@extends('admin.layouts.app')
@section('title', 'Kategori Projek')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Kategori Projek']]" />
<x-admin.section-title title="Kategori Projek" subtitle="Kelola kategori projek">
    <x-slot:actions><x-admin.add-button route="admin.project-categories.create" label="Tambah Kategori" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.project-categories.index" placeholder="Cari kategori..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th>Nama</x-admin.th>
        <x-admin.th>Slug</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-16">Urutan</x-admin.th>
        <x-admin.th class="w-28">Dibuat Pada</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($categories as $index => $c)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $categories->firstItem() + $index }}</x-admin.td>
        <x-admin.td class="font-medium">{{ $c->name }}</x-admin.td>
        <x-admin.td class="text-gray-500 font-mono text-xs">{{ $c->slug }}</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="project_categories" :id="$c->id" :checked="$c->status" /></x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $c->sort_order ?? 0 }}</x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $c->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.project-categories.edit" destroy="admin.project-categories.destroy" :params="[$c]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="7" message="Belum ada kategori." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $categories->firstItem() ?? 0 }} sampai {{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} data</p>
        {{ $categories->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
