<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'sort_order', 'is_featured', 'status',
        'url', 'client', 'year', 'duration', 'about', 'result_text', 'stats', 'budget',
    ];

    protected function casts(): array
    {
        return [
            'about' => 'array',
            'stats' => 'array',
        ];
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->categories->first()?->slug) {
            'company' => 'bg-blue-600/90 text-white',
            'catalog' => 'bg-yellow-500/90 text-[#1a1a2e]',
            'umkm' => 'bg-green-500/90 text-white',
            'sistem' => 'bg-purple-500/90 text-white',
            'landing' => 'bg-rose-500/90 text-white',
            'custom' => 'bg-orange-500/90 text-white',
            default => 'bg-blue-600/90 text-white',
        };
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProjectCategory::class, 'project_category');
    }

    public function getFirstCategoryAttribute(): ?ProjectCategory
    {
        return $this->categories->first();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function coverImage(): ?ProjectImage
    {
        return $this->images->firstWhere('type', 'cover') ?? $this->images->first();
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class);
    }

    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ProjectResult::class);
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(ProjectTestimonial::class);
    }
}
