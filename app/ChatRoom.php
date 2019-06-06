<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatRoom extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_chat_room')->withPivot(['is_read']);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
