<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtaSection extends Model
{
    protected $fillable = ['title', 'description', 'button_text', 'button_url', 'is_active'];
}
