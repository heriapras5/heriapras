@php
    $developerName = 'Heri A Pras';
@endphp
<footer id="footer" class="bg-night border-t border-white/5 text-body">
    <div class="max-w-6xl mx-auto px-5 py-5 text-center text-sm text-body">
        <p>&copy; <span data-current-year>{{ date('Y') }}</span> Developed By {{ $developerName }}</p>
    </div>
</footer>
