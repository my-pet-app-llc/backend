<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    public $fillable = [
        'owner_id', 'name', 'gender',
        'size', 'primary_breed', 'secondary_breed',
        'age', 'profile_picture', 'friendliness',
        'activity_level', 'noise_level', 'odebience_level',
        'fetchability', 'swimability', 'like',
        'dislike', 'favorite_toys', 'fears',
        'favorite_places', 'spayed', 'birthday',
        'favorite_park'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function pictures()
    {
        return $this->morphMany(Picture::class, 'picturable');
    }
}
