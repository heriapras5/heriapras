<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = ['title', 'subtitle', 'description', 'vision', 'mission', 'image', 'is_active'];

    protected function casts(): array
    {
        return ['mission' => 'array'];
    }
}
