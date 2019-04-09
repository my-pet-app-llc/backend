<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    const TYPES = [
        'text' => 1,
        'image' => 2,
        'event' => 3
    ];

    protected $fillable = [
        'chat_room_id', 'sender_id', 'type', 'is_like'
    ];

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function sender()
    {
        return $this->belongsTo(Pet::class, 'sender_id', 'id');
    }

    public function messagable()
    {
        return $this->morphTo();
    }
}
