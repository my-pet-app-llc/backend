<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = [
        'image', 'title', 'description'
    ];
}
