<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTestimonial extends Model
{
    protected $fillable = ['project_id', 'quote', 'name', 'role', 'avatar'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
