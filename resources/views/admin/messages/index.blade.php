@extends('admin.layouts.app')
@section('title', 'Pesan Masuk')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pesan Masuk']]" />
<x-admin.section-title title="Pesan Masuk" subtitle="Pesan dari pengunjung website" />
<x-admin.search route="admin.messages.index" placeholder="Cari pesan..." :filterNames="['search', 'status']">
    <x-slot:filters>
        <select name="status" class="admin-input w-36">
            <option value="all" @selected(($status ?? 'all') === 'all')>Semua Status</option>
            <option value="unread" @selected(($status ?? 'all') === 'unread')>Baru</option>
            <option value="read" @selected(($status ?? 'all') === 'read')>Terbaca</option>
        </select>
    </x-slot:filters>
</x-admin.search>
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th>Nama</x-admin.th>
        <x-admin.th>Email</x-admin.th>
        <x-admin.th>Layanan</x-admin.th>
        <x-admin.th class="w-20">Status</x-admin.th>
        <x-admin.th class="w-28">Tanggal</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($messages as $index => $m)
    <tr class="hover:bg-blue-50/30 transition-colors {{ !$m->is_read ? 'bg-blue-50/40 font-medium' : '' }}">
        <x-admin.td class="text-gray-400 text-xs">{{ $messages->firstItem() + $index }}</x-admin.td>
        <x-admin.td>{{ $m->name }}</x-admin.td>
        <x-admin.td class="text-gray-500">{{ $m->email }}</x-admin.td>
        <x-admin.td>{{ $m->service ? \Str::limit($m->service, 30) : '-' }}</x-admin.td>
        <x-admin.td>
            @if($m->is_read)
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>Terbaca
            </span>
            @else
            <x-admin.badge color="blue">Baru</x-admin.badge>
            @endif
        </x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $m->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td>
            <x-admin.actions show="admin.messages.show" destroy="admin.messages.destroy" :params="[$m]">
                <form method="POST" action="{{ route('admin.messages.toggle-read', $m) }}" class="inline">
                    @csrf @method('PATCH')
                    <button type="submit" title="{{ $m->is_read ? 'Tandai belum dibaca' : 'Tandai sudah dibaca' }}"
                            class="p-2 rounded-lg transition {{ $m->is_read ? 'text-gray-400 hover:bg-gray-100' : 'text-blue-600 hover:bg-blue-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4z"/></svg>
                    </button>
                </form>
            </x-admin.actions>
        </x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="7" message="Belum ada pesan masuk." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $messages->firstItem() ?? 0 }} sampai {{ $messages->lastItem() ?? 0 }} dari {{ $messages->total() }} data</p>
        {{ $messages->appends(['search' => request('search'), 'status' => request('status')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
