@php
    $topEmail = setting('footer_email');
    $topPhone = setting('whatsapp_number');
@endphp
<div class="hidden md:block bg-night border-b border-white/5">
    <div class="max-w-6xl mx-auto px-5 h-9 flex items-center justify-between text-xs text-muted">
        <div class="flex items-center gap-5">
            @if($topEmail)
                <a href="mailto:{{ $topEmail }}" class="inline-flex items-center gap-1.5 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    {{ $topEmail }}
                </a>
            @endif
            @if($topPhone)
                <a href="https://wa.me/{{ $topPhone }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    {{ $topPhone }}
                </a>
            @endif
            <span class="inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Sen&ndash;Jum, 09.00&ndash;18.00 WIB
            </span>
        </div>
        <div class="flex items-center gap-2">
            @if(setting('social_instagram'))
                <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" aria-label="Instagram" class="w-7 h-7 rounded-full border border-white/10 inline-flex items-center justify-center hover:text-white hover:border-primary"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
            @endif
            @if(setting('github_url'))
                <a href="{{ setting('github_url') }}" target="_blank" rel="noopener" aria-label="GitHub" class="w-7 h-7 rounded-full border border-white/10 inline-flex items-center justify-center hover:text-white hover:border-primary"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg></a>
            @endif
            @if(setting('social_linkedin'))
                <a href="{{ setting('social_linkedin') }}" target="_blank" rel="noopener" aria-label="LinkedIn" class="w-7 h-7 rounded-full border border-white/10 inline-flex items-center justify-center hover:text-white hover:border-primary"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
            @endif
        </div>
    </div>
</div>
