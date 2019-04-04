<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ChatEventMessage extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'event_id', 'event_invite_id', 'deleted_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return MorphOne
     */
    public function message()
    {
        return $this->morphOne(ChatMessage::class, 'messagable');
    }

    /**
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventInvite()
    {
        return $this->belongsTo(EventInvite::class);
    }
}
