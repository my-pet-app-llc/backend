<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventInvite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id', 'pet_id', 'accepted'
    ];

    protected $dates = ['deleted_at'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
