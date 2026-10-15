@extends('admin.layouts.app')
@section('title', 'Paket')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Paket']]" />
<x-admin.section-title title="Daftar Paket" subtitle="Kelola paket harga website">
    <x-slot:actions><x-admin.add-button route="admin.packages.create" label="Tambah Paket" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.packages.index" placeholder="Cari paket..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th>Nama Paket</x-admin.th>
        <x-admin.th class="w-40">Harga</x-admin.th>
        <x-admin.th class="w-28">Badge</x-admin.th>
        <x-admin.th class="w-16">Urutan</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($packages as $index => $p)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $packages->firstItem() + $index }}</x-admin.td>
        <x-admin.td class="font-medium">{{ $p->name }}</x-admin.td>
        <x-admin.td class="text-gray-600 text-xs font-medium">{{ $p->price }}</x-admin.td>
        <x-admin.td>
            @if($p->badge)
            <x-admin.badge color="amber" soft class="text-[11px]">{{ $p->badge }}</x-admin.badge>
            @else
            <span class="text-gray-400">-</span>
            @endif
        </x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $p->sort_order ?? 0 }}</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="packages" :id="$p->id" :checked="$p->status" /></x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.packages.edit" destroy="admin.packages.destroy" :params="[$p]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="7" message="Belum ada paket." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $packages->firstItem() ?? 0 }} sampai {{ $packages->lastItem() ?? 0 }} dari {{ $packages->total() }} data</p>
        {{ $packages->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
