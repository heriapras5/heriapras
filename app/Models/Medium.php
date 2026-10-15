<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    protected $fillable = ['name', 'file_name', 'mime_type', 'path', 'size', 'sort_order'];

    protected $table = 'media';
}
