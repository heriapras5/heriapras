@props([
    'edit' => null,
    'show' => null,
    'showUrl' => null,
    'showTarget' => null,
    'destroy' => null,
    'params' => [],
])

<div class="flex items-center justify-end gap-1">
    @if($show || $showUrl)
    <a href="{{ $showUrl ?? route($show, $params) }}" title="Lihat"
       @if($showTarget)target="{{ $showTarget }}" @endif
       class="p-2 rounded-lg hover:bg-sky-50 text-gray-400 hover:text-sky-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
    </a>
    @endif
    @if($edit)
    <a href="{{ route($edit, $params) }}" title="Edit"
       class="p-2 rounded-lg hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
    </a>
    @endif
    @if($destroy)
    <button type="button" onclick="confirmDelete('{{ route($destroy, $params) }}')" title="Hapus"
            class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
    </button>
    @endif
    {{ $slot }}
</div>
