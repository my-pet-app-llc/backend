<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventInvite extends Model
{
    protected $fillable = [
        'event_id', 'pet_id', 'accepted'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
