@extends('admin.layouts.app')
@section('title', $user->exists ? 'Edit Admin' : 'Tambah Admin')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Admin', 'url' => route('admin.users.index')], ['label' => $user->exists ? 'Edit' : 'Tambah']]" />
<div class="max-w-xl">
    <x-admin.form-card :title="$user->exists ? 'Edit Admin' : 'Tambah Admin'" :description="$user->exists ? 'Ubah data admin.' : 'Buat akun admin baru.'">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-xs mb-5">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf @if($user->exists) @method('PUT') @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="name" label="Nama" :value="$user->name" required />
                <x-admin.input name="email" label="Email" type="email" :value="$user->email" required />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.input name="password" label="Password" type="password" :required="! $user->exists" :help="$user->exists ? 'Kosongkan jika tidak diubah.' : null" />
                <x-admin.input name="password_confirmation" label="Konfirmasi Password" type="password" :required="! $user->exists" />
            </div>
            <div>
                <label class="form-label">Role</label>
                @if($user->id === 1 || $user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role ?: 'super_admin' }}">
                <div class="admin-input bg-gray-50 text-gray-500">{{ ($user->role ?? 'super_admin') === 'super_admin' ? 'Super Admin' : 'Admin' }} (tidak dapat diubah untuk akun ini)</div>
                @else
                <select name="role" class="admin-input bg-white">
                    <option value="admin" @selected(old('role', $user->role ?? 'admin') === 'admin')>Admin</option>
                    <option value="super_admin" @selected(old('role', $user->role ?? 'admin') === 'super_admin')>Super Admin</option>
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @endif
            </div>
            <x-admin.form-actions :cancelUrl="route('admin.users.index')" submitLabel="Simpan" />
        </form>
    </x-admin.form-card>
</div>
@endsection
