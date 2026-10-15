<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = ['icon', 'group_name', 'type', 'title', 'slug', 'description', 'percent', 'features', 'sort_order', 'status'];

    protected function casts(): array
    {
        return ['features' => 'array'];
    }
}
