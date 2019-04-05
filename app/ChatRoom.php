<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_chat_room')->withPivot(['is_read']);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
