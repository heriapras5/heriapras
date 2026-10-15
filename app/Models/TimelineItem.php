<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineItem extends Model
{
    protected $table = 'portfolio_timeline_items';

    protected $fillable = [
        'type',
        'title',
        'organization',
        'period',
        'description',
        'sort_order',
        'is_active',
    ];
}
