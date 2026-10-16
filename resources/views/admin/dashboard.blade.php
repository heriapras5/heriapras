@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

<x-admin.breadcrumb :items="[['label' => 'Dashboard']]" />

<x-admin.section-title title="Dashboard" subtitle="Kelola konten Hero dan Portfolio">
    <x-slot:actions>
        <span class="text-xs text-[#94a3b8] hidden md:inline">{{ now()->translatedFormat('l, d F Y') }}</span>
    </x-slot:actions>
</x-admin.section-title>

<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @foreach($contentSummary as $s)
    <a href="{{ $s['route'] }}" class="group admin-card hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-4 flex items-center gap-3 no-underline">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $s['colors']['bg'] }} flex items-center justify-center text-white shrink-0">
            <x-admin.nav-icon :name="$s['icon']" />
        </div>
        <div class="min-w-0">
            <div class="text-xl font-extrabold text-[#0f172a] leading-none">{{ number_format($s['count']) }}</div>
            <div class="text-[11px] font-medium text-[#64748b] truncate">{{ $s['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

<div class="admin-card p-5 sm:p-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2">
            <span class="h-5 w-1.5 rounded-full bg-gradient-to-b from-blue-600 to-blue-400 shrink-0"></span>
            <h3 class="text-base font-bold text-[#1e293b]">Projek Terbaru</h3>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 no-underline">Kelola projek &rarr;</a>
    </div>
    @forelse($latestProjects as $p)
    <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
        <div class="flex-1 min-w-0">
            <span class="block text-sm font-semibold text-[#1e293b] truncate">{{ $p->name }}</span>
            <span class="block text-[11px] text-[#94a3b8] truncate">{{ $p->slug }} &middot; {{ $p->year ?? '-' }}</span>
        </div>
        <a href="{{ route('admin.projects.edit', $p) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 no-underline shrink-0">Edit</a>
    </div>
    @empty
    <p class="text-sm text-[#94a3b8] text-center py-8">Belum ada projek.</p>
    @endforelse
</div>
@endsection
