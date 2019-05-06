<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Picture extends Model
{
    public $fillable = [
        'picture'
    ];

    public function picturable()
    {
        return $this->morphTo();
    }

    public function getImgUrl()
    {
        return Storage::url($this->picture);
    }
}
