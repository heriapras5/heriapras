@extends('admin.layouts.app')
@section('title', 'Manajemen Admin')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Manajemen Admin']]" />
<x-admin.section-title title="Manajemen Admin" subtitle="Kelola akun admin">
    <x-slot:actions><x-admin.add-button route="admin.users.create" label="Tambah Admin" /></x-slot:actions>
</x-admin.section-title>
<x-admin.search route="admin.users.index" placeholder="Cari nama atau email..." />
<x-admin.table>
    <x-slot:head>
        <x-admin.th class="w-10">No</x-admin.th>
        <x-admin.th>Nama</x-admin.th>
        <x-admin.th>Email</x-admin.th>
        <x-admin.th class="w-28">Role</x-admin.th>
        <x-admin.th class="w-28">Bergabung</x-admin.th>
        <x-admin.th class="w-24 text-right">Aksi</x-admin.th>
    </x-slot:head>
    @forelse($users as $index => $u)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <x-admin.td class="text-gray-400 text-xs">{{ $users->firstItem() + $index }}</x-admin.td>
        <x-admin.td class="font-medium">{{ $u->name }}</x-admin.td>
        <x-admin.td class="text-gray-500">{{ $u->email }}</x-admin.td>
        <x-admin.td>
            <x-admin.badge :color="$u->isSuperAdmin() ? 'amber' : 'blue'">{{ $u->roleLabel() }}</x-admin.badge>
        </x-admin.td>
        <x-admin.td class="text-gray-400 text-xs">{{ $u->created_at->format('d M Y H:i') }}</x-admin.td>
        <x-admin.td>
            @if($u->id !== 1 && $u->id !== auth()->id())
            <x-admin.actions edit="admin.users.edit" destroy="admin.users.destroy" :params="[$u]" />
            @else
            <span class="text-gray-400 text-xs">{{ $u->id === 1 ? 'Super Admin' : 'Akun sendiri' }}</span>
            @endif
        </x-admin.td>
    </tr>
    @empty
    <x-admin.empty-state :colspan="6" message="Belum ada admin." />
    @endforelse
    <x-slot:footer>
        <p>Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</p>
        {{ $users->appends(['search' => request('search')])->links() }}
    </x-slot:footer>
</x-admin.table>
@endsection
