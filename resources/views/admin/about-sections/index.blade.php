@extends('admin.layouts.app')
@section('title', 'Tentang')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Tentang']]" />
<x-admin.section-title title="Tentang Kami" subtitle="Profil, visi, misi perusahaan">
    <x-slot:actions><x-admin.add-button route="admin.about-sections.create" label="Tambah" /></x-slot:actions>
</x-admin.section-title>
<x-admin.table>
    <x-slot:head>
        <x-admin.th>Judul</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($sections as $s)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td>
            <div class="font-medium">{{ $s->title }}</div>
            @if($s->subtitle)<div class="text-xs text-gray-400">{{ $s->subtitle }}</div>@endif
        </x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="about_sections" :id="$s->id" :checked="$s->is_active" /></x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.about-sections.edit" destroy="admin.about-sections.destroy" :params="[$s]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="3" message="Belum ada data." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $sections->firstItem() ?? 0 }} sampai {{ $sections->lastItem() ?? 0 }} dari {{ $sections->total() }} data</p>
        {{ $sections->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
