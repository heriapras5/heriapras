@extends('admin.layouts.app')
@section('title', 'Hero Section')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Hero / Slider']]" />
<x-admin.section-title title="Daftar Hero / Slider" subtitle="Kelola slide hero halaman utama">
    <x-slot:actions><x-admin.add-button route="admin.hero-sections.create" label="Tambah Slide" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.hero-sections.index" placeholder="Cari hero..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th class="w-16">Gambar</x-admin.th>
        <x-admin.th>Judul</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-16">Urutan</x-admin.th>
        <x-admin.th class="w-28">Dibuat Pada</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($heroSections as $index => $hs)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $heroSections->firstItem() + $index }}</x-admin.td>
        <x-admin.td>
            @if($hs->image)
            <img src="{{ asset($hs->image) }}" class="w-14 h-10 object-cover rounded-lg shadow-sm border border-gray-100">
            @else
            <div class="w-14 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            </div>
            @endif
        </x-admin.td>
        <x-admin.td class="font-medium">{{ $hs->title }}</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="hero_sections" :id="$hs->id" :checked="$hs->is_active" /></x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $hs->sort_order ?? 0 }}</x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $hs->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.hero-sections.edit" destroy="admin.hero-sections.destroy" :params="[$hs]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="7" message="Belum ada slide hero." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $heroSections->firstItem() ?? 0 }} sampai {{ $heroSections->lastItem() ?? 0 }} dari {{ $heroSections->total() }} data</p>
        {{ $heroSections->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
