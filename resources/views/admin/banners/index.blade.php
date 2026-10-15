@extends('admin.layouts.app')
@section('title', 'Banner')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Banner']]" />
<x-admin.section-title title="Banner Slider" subtitle="Kelola banner slider halaman utama">
    <x-slot:actions><x-admin.add-button route="admin.banners.create" label="Tambah Banner" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.banners.index" placeholder="Cari banner..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th>Judul</x-admin.th>
        <x-admin.th class="w-24">Gambar</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($banners as $b)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td>
            <div class="font-medium">{{ $b->title }}</div>
            @if($b->subtitle)<div class="text-xs text-gray-400 font-normal">{{ $b->subtitle }}</div>@endif
        </x-admin.td>
        <x-admin.td>
            @if($b->image)
            <img src="{{ asset($b->image) }}" class="w-16 h-10 object-cover rounded-lg border border-gray-100">
            @else
            <span class="text-gray-400">-</span>
            @endif
        </x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="banners" :id="$b->id" :checked="$b->is_active" /></x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.banners.edit" destroy="admin.banners.destroy" :params="[$b]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="4" message="Belum ada banner." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $banners->firstItem() ?? 0 }} sampai {{ $banners->lastItem() ?? 0 }} dari {{ $banners->total() }} data</p>
        {{ $banners->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
