<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'pageable_id', 'pageable_type', 'title', 'description', 'keywords',
        'og_title', 'og_description', 'og_image', 'canonical_url',
    ];

    protected $table = 'seo_meta';

    public function pageable(): MorphTo
    {
        return $this->morphTo();
    }
}
