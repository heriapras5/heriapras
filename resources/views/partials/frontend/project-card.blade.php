@php
    $isClone = $isClone ?? false;
    $projectCategory = $project->categories->first();
    $filterClass = $projectCategory ? ($projectCategory->slug ?: \Illuminate\Support\Str::slug($projectCategory->name)) : 'lainnya';
    $cover = $project->coverImage();
    $coverExists = $cover && $cover->image
        && ! str_starts_with((string) $cover->image, 'http')
        && is_file(public_path(ltrim((string) $cover->image, '/')));
@endphp
<article data-portfolio-item @if(!$isClone) data-portfolio-original @else data-portfolio-clone @endif class="portfolio-card filter-{{ $filterClass }} card-soft card-hover overflow-hidden flex flex-col @if(!$isClone) reveal @endif" @if($isClone) aria-hidden="true" @endif>
    <div class="relative">
        @if($coverExists)
            <img src="{{ asset($cover->image) }}" alt="{{ $isClone ? '' : 'Screenshot '.$project->name }}" @if(!$isClone) loading="lazy" @endif class="w-full aspect-[16/10] object-cover" onerror="this.style.display='none';if(this.nextElementSibling)this.nextElementSibling.style.display='flex'">
            <div class="w-full aspect-[16/10] bg-primary/20 flex-col items-center justify-center gap-1 p-4 text-center" style="display:none" aria-hidden="true">
                <span class="font-bold text-white">{{ \Illuminate\Support\Str::limit($project->name, 28) }}</span>
                <small class="text-muted">{{ $projectCategory->name ?? 'Projek' }}</small>
            </div>
        @else
            <div class="w-full aspect-[16/10] bg-primary/20 flex flex-col items-center justify-center gap-1 p-4 text-center">
                <span class="font-bold text-white">{{ \Illuminate\Support\Str::limit($project->name, 28) }}</span>
                <small class="text-muted">{{ $projectCategory->name ?? 'Projek' }}</small>
            </div>
        @endif
    </div>
    <div class="p-5 flex flex-col flex-1">
        <h3 class="font-bold text-white text-[15px] leading-tight">{{ $project->name }}</h3>
        <p class="text-[13px] text-muted mt-1 flex-1">{{ \Illuminate\Support\Str::limit(strip_tags($project->description ?: ($projectCategory->name ?? 'Project')), 90) }}</p>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs font-bold text-muted">{{ $projectCategory->name ?? 'Projek' }}{{ $project->year ? ' · '.$project->year : '' }}</span>
            <a href="{{ route('projects.detail', $project->slug) }}" @if($isClone) tabindex="-1" @endif class="inline-flex items-center gap-1.5 text-xs font-bold text-glow hover:text-white" aria-label="Lihat detail {{ $project->name }}">
                {{ setting('ui_label_view_project') ?: 'Lihat Detail' }}
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
            </a>
        </div>
    </div>
</article>
