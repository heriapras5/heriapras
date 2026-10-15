<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'price', 'old_price', 'tagline', 'facilities', 'renewal', 'badge', 'sort_order', 'status'];

    protected function casts(): array
    {
        return ['facilities' => 'array'];
    }
}
