<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportChatRoom extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'is_read', 'ticket_id', 'owner_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function supportChatMessages()
    {
        return $this->hasMany(SupportChatMessage::class);
    }
}
