<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

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

    protected $dates = ['deleted_at'];

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

    public function convertDateTimeAttributesToTimezone ($utc)
    {
        $fromDateTime = Carbon::parse($this->attributes['from_date'] . ' ' . $this->attributes['from_time'])->addHours($utc);
        $toDateTime = Carbon::parse($this->attributes['from_date'] . ' ' . $this->attributes['to_time'])->addHours($utc);

        $this->attributes['from_date'] = $fromDateTime->format('Y-m-d');
        $this->attributes['from_time'] = $fromDateTime->format('H:i');
        $this->attributes['to_time'] = $toDateTime->format('H:i');

        return $this;
    }

    public function convertDateTimeAttributesFromTimezoneToUTC ($utc)
    {
        $fromDateTime = Carbon::parse($this->attributes['from_date'] . ' ' . $this->attributes['from_time'])->subHours($utc);
        $toDateTime = Carbon::parse($this->attributes['from_date'] . ' ' . $this->attributes['to_time'])->subHours($utc);

        $this->attributes['from_date'] = $fromDateTime->format('Y-m-d');
        $this->attributes['from_time'] = $fromDateTime->format('H:i');
        $this->attributes['to_time'] = $toDateTime->format('H:i');

        return $this;
    }
}
