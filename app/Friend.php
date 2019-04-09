<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'pet_id', 'friend_id'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function friend()
    {
        return $this->belongsTo(Pet::class, 'friend_id', 'id');
    }
}
