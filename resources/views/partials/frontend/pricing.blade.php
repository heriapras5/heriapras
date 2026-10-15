@if(isset($packages) && $packages->isNotEmpty())
<section id="packages" class="bg-cream">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-18">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,9fr)]">
            <div class="reveal">
                <p class="eyebrow">Layanan &amp; Paket</p>
                <h2 class="section-title mt-2">Paket Simpel.<br><span class="script-accent-purple text-4xl">Dampak Besar.</span></h2>
                <p class="mt-2 text-sm">Solusi fleksibel untuk bisnis di setiap tahap.</p>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach($packages as $index => $package)
                    @php
                        $isPopular = $index === 1;
                        $facilities = is_array($package->facilities) ? $package->facilities : [];
                    @endphp
                    <div class="reveal card-soft card-hover p-6 flex flex-col {{ $isPopular ? '!border-2 !border-sunshine' : '' }}">
                        @if($isPopular || $package->badge)
                            <p class="-mx-6 -mt-6 mb-4 rounded-t-[18px] {{ $isPopular ? 'bg-sunshine text-heading' : 'bg-primary-soft text-primary' }} text-center text-[11px] font-extrabold tracking-wider uppercase py-2">{{ strtoupper($package->badge ?: 'Paling Populer') }}</p>
                        @endif
                        <h3 class="text-xs font-extrabold tracking-[0.14em] uppercase text-primary">{{ $package->name }}</h3>
                        <p class="mt-2 text-heading font-extrabold text-2xl">
                            Rp{{ number_format((float) $package->price, 0, ',', '.') }}
                        </p>
                        @if($package->old_price)
                            <p class="text-xs text-muted line-through">Rp{{ number_format((float) $package->old_price, 0, ',', '.') }}</p>
                        @endif
                        @if($package->tagline)
                            <p class="mt-1 text-xs">{{ $package->tagline }}</p>
                        @endif
                        @if(count($facilities))
                            <ul class="mt-4 space-y-2 text-[13px] flex-1">
                                @foreach($facilities as $facility)
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary shrink-0 mt-0.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                        <span>{{ is_array($facility) ? ($facility['text'] ?? $facility['label'] ?? '') : $facility }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('home') }}#contact" data-service-prefill="{{ $package->name }}" class="btn-pill mt-5 justify-center {{ $isPopular ? 'btn-sunshine' : 'btn-outline' }}">Mulai</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
