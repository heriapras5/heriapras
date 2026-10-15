@if($educationItems->isNotEmpty() || $experienceItems->isNotEmpty())
<section id="resume" class="bg-abyss border-t border-white/5">
    <div class="max-w-6xl mx-auto px-5 py-14 md:py-20">
        <div class="reveal text-center max-w-xl mx-auto">
            <p class="eyebrow">{{ setting('ui_eyebrow_resume') ?: 'Perjalanan Saya' }}</p>
            <h2 class="section-title mt-2">{{ setting('landing_resume_title') ?: 'Perjalanan' }}</h2>
            <p class="mt-2 text-sm text-muted">{{ setting('landing_resume_subtitle') ?: 'Dari bangku pendidikan hingga membangun produk digital yang dipakai untuk kebutuhan nyata.' }}</p>
        </div>
        <div class="mt-8 grid gap-5 md:grid-cols-2">
            <div class="reveal card-soft p-6">
                <h3 class="font-extrabold text-white text-lg">Pendidikan</h3>
                <ol class="mt-4 space-y-5">
                    @forelse($educationItems as $item)
                        <li class="relative pl-5 border-l-2 border-primary/40">
                            <span class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-primary" aria-hidden="true"></span>
                            <h4 class="font-bold text-white">{{ $item->title }}</h4>
                            @if($item->period)<p class="text-xs font-bold text-glow mt-0.5">{{ $item->period }}</p>@endif
                            @if($item->organization)<p class="text-sm italic mt-0.5 text-muted">{{ $item->organization }}</p>@endif
                            @if($item->description)<p class="text-sm mt-1.5 leading-relaxed">{{ $item->description }}</p>@endif
                        </li>
                    @empty
                        <li><p class="text-sm text-muted">Belum ada data pendidikan.</p></li>
                    @endforelse
                </ol>
            </div>
            <div class="reveal card-soft p-6">
                <h3 class="font-extrabold text-white text-lg">Pengalaman &amp; Belajar</h3>
                <ol class="mt-4 space-y-5">
                    @forelse($experienceItems as $item)
                        <li class="relative pl-5 border-l-2 border-primary/40">
                            <span class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-glow" aria-hidden="true"></span>
                            <h4 class="font-bold text-white">{{ $item->title }}</h4>
                            @if($item->period)<p class="text-xs font-bold text-glow mt-0.5">{{ $item->period }}</p>@endif
                            @if($item->organization)<p class="text-sm italic mt-0.5 text-muted">{{ $item->organization }}</p>@endif
                            @if($item->description)<p class="text-sm mt-1.5 leading-relaxed">{{ $item->description }}</p>@endif
                        </li>
                    @empty
                        <li><p class="text-sm text-muted">Belum ada data pengalaman.</p></li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</section>
@endif
