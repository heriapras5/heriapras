<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = ['ip_address', 'user_agent', 'page_url', 'page_path', 'device_type'];
}
