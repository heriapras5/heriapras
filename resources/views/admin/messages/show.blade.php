@extends('admin.layouts.app')
@section('title', 'Detail Pesan')
@section('content')
<x-admin.breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pesan Masuk', 'url' => route('admin.messages.index')], ['label' => 'Detail']]" />
<div class="max-w-2xl">
    <x-admin.form-card title="Detail Pesan">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-md shrink-0">
                {{ strtoupper(substr($message->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-bold text-gray-900 truncate">{{ $message->name }}</h3>
                <p class="text-[13px] text-gray-500 truncate">{{ $message->email }}</p>
            </div>
            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $message->created_at->format('d M Y H:i') }}</span>
        </div>
        @if($message->phone)
        <div class="mb-4 p-4 bg-gray-50 rounded-xl">
            <span class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">No. Telepon</span>
            <p class="text-[13px] font-medium mt-0.5">{{ $message->phone }}</p>
        </div>
        @endif
        @if($message->service)
        <div class="mb-4">
            <span class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">Layanan</span>
            <p class="text-[13px] font-medium mt-0.5">{{ $message->service }}</p>
        </div>
        @endif
        <div class="mb-5">
            <span class="text-[11px] text-gray-500 font-medium uppercase tracking-wider">Pesan</span>
            <div class="mt-2 p-4 bg-gray-50 rounded-xl">
                <p class="text-[13px] text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $message->message }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100">
            <form method="POST" action="{{ route('admin.messages.toggle-read', $message) }}" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-secondary btn-sm">
                    {{ $message->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                </button>
            </form>
            <a href="mailto:{{ $message->email }}" class="btn btn-primary btn-sm">Balas Email</a>
            <a href="https://wa.me/{{ $message->phone ?: setting('whatsapp_number', '6285736186308') }}" target="_blank" class="btn btn-secondary btn-sm">Chat WhatsApp</a>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
        </div>
    </x-admin.form-card>
</div>
@endsection
