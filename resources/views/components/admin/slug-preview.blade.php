@props([
    'source' => 'title',
    'base' => '',
])

@php
    $base = trim((string) $base, '/');
@endphp

<div class="slug-preview mt-1.5 flex items-center gap-1.5 text-[11px] text-gray-400" data-source="{{ $source }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
    <span class="font-mono text-gray-500">/{{ $base ? $base . '/' : '' }}<span class="slug-slug text-blue-600 font-semibold">…</span></span>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function slugifyPreview(s) {
        return s.toLowerCase()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
    document.querySelectorAll('.slug-preview').forEach(function (el) {
        var src = document.getElementById(el.dataset.source);
        if (!src) return;
        var out = el.querySelector('.slug-slug');
        var upd = function () { out.textContent = slugifyPreview(src.value) || '…'; };
        src.addEventListener('input', upd);
        upd();
    });
});
</script>
@endpush
@endonce