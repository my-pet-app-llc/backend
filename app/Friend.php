<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pet_id', 'friend_id'
    ];

    protected $dates = ['deleted_at'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function friend()
    {
        return $this->belongsTo(Pet::class, 'friend_id', 'id');
    }
}
