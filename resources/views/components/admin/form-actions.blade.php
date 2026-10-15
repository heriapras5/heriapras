@props([
    'cancelUrl' => null,
    'submitLabel' => 'Simpan Data',
    'submitIcon' => null,
    'loadingLabel' => 'Menyimpan…',
])

<div class="flex items-center justify-end gap-3 pt-2">
    @if($cancelUrl)
    <a href="{{ $cancelUrl }}" class="btn btn-secondary">Batal</a>
    @endif
    <button type="submit" class="btn btn-primary" data-loading-btn data-loading-label="{{ $loadingLabel }}">
        <span class="loading-btn-icon">{!! $submitIcon ?? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>' !!}</span>
        <span class="loading-btn-text">{{ $submitLabel }}</span>
    </button>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', function (e) {
        var btn = e.target.querySelector('[data-loading-btn]');
        if (!btn || btn.dataset.loading) return;
        if (typeof e.target.checkValidity === 'function' && !e.target.checkValidity()) return;
        btn.dataset.loading = '1';
        btn.disabled = true;
        btn.dataset.original = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="animate-spin inline"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> ' + btn.dataset.loadingLabel;
    }, true);
    document.addEventListener('invalid', function (e) {
        var form = e.target && e.target.form;
        if (!form) return;
        var btn = form.querySelector('[data-loading-btn]');
        if (btn && btn.dataset.loading) {
            btn.dataset.loading = '';
            btn.disabled = false;
            if (btn.dataset.original) {
                btn.innerHTML = btn.dataset.original;
                delete btn.dataset.original;
            }
        }
    }, true);
});
</script>
@endpush
@endonce