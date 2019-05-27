<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'image', 'title', 'short_text', 'full_text', 'address', 'phone_number', 'website', 'state'
    ];
}
