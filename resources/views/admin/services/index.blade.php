@extends('admin.layouts.app')
@section('title', 'Teknologi Hero')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Teknologi Hero']]" />
<x-admin.section-title title="Teknologi Hero" subtitle="Ikon teknologi yang tampil pada Hero Section halaman utama">
    <x-slot:actions><x-admin.add-button route="admin.services.create" label="Tambah Teknologi" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.services.index" placeholder="Cari teknologi..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th class="w-12">Ikon</x-admin.th>
        <x-admin.th>Judul</x-admin.th>
        <x-admin.th class="w-24">Fitur</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-16">Urutan</x-admin.th>
        <x-admin.th class="w-28">Dibuat Pada</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($services as $index => $s)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $services->firstItem() + $index }}</x-admin.td>
        <x-admin.td>
            <div class="w-8 h-8 bg-blue-600/10 rounded-lg flex items-center justify-center text-blue-600 [&>svg]:w-4 [&>svg]:h-4">{!! safeIcon($s->icon) !!}</div>
        </x-admin.td>
        <x-admin.td class="font-medium">{{ $s->title }}</x-admin.td>
        <x-admin.td class="text-xs text-gray-500">{{ is_array($s->features) ? count($s->features) : 0 }} fitur</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="services" :id="$s->id" :checked="$s->status" /></x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $s->sort_order ?? 0 }}</x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $s->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.services.edit" destroy="admin.services.destroy" :params="[$s]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="8" message="Belum ada teknologi hero." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $services->firstItem() ?? 0 }} sampai {{ $services->lastItem() ?? 0 }} dari {{ $services->total() }} data</p>
        {{ $services->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
