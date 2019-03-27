<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    public $fillable = [
        'picture'
    ];

    public function picturable()
    {
        return $this->morphTo();
    }
}
