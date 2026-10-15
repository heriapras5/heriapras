<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = ['type', 'label', 'value', 'icon', 'sort_order', 'status'];

    protected $table = 'contact_info';
}
