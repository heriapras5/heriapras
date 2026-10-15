@extends('admin.layouts.app')
@section('title', 'FAQ')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'FAQ']]" />
<x-admin.section-title title="Daftar FAQ" subtitle="Kelola pertanyaan yang sering diajukan">
    <x-slot:actions><x-admin.add-button route="admin.faqs.create" label="Tambah FAQ" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.faqs.index" placeholder="Cari FAQ..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th>Pertanyaan</x-admin.th>
        <x-admin.th class="w-28">Status</x-admin.th>
        <x-admin.th class="w-16">Urutan</x-admin.th>
        <x-admin.th class="w-28">Dibuat Pada</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($faqs as $index => $f)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $faqs->firstItem() + $index }}</x-admin.td>
        <x-admin.td class="font-medium">{{ \Str::limit($f->question, 60) }}</x-admin.td>
        <x-admin.td><x-admin.status-toggle resource="faqs" :id="$f->id" :checked="$f->status" /></x-admin.td>
        <x-admin.td class="text-gray-500 text-xs">{{ $f->sort_order ?? 0 }}</x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $f->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td><x-admin.actions edit="admin.faqs.edit" destroy="admin.faqs.destroy" :params="[$f]" /></x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="6" message="Belum ada FAQ." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $faqs->firstItem() ?? 0 }} sampai {{ $faqs->lastItem() ?? 0 }} dari {{ $faqs->total() }} data</p>
        {{ $faqs->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
