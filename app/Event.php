<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'pet_id', 'type', 'name',
        'from_date', 'from_time', 'to_time',
        'repeat', 'where', 'notes'
    ];

    protected $casts = [
        'repeat' => 'array'
    ];

    const TYPES = [
        'social' => 1,
        'care' => 2
    ];

    /**
     * @return BelongsTo
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function eventInvites()
    {
        return $this->hasMany(EventInvite::class);
    }

    public function invitedPets()
    {
        return $this->belongsToMany(Pet::class, 'event_invites');
    }

    /**
     * @return HasMany
     */
    public function chatEventMessages()
    {
        return $this->hasMany(ChatEventMessage::class);
    }
}
