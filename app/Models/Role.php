<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'is_system', 'sort_order', 'status'];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
