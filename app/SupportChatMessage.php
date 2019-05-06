<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportChatMessage extends Model
{
    protected $fillable = [
        'type', 'is_like', 'support_chat_room_id', 'sender_id'
    ];

    const TYPES = [
        'system' => 1,
        'text' => 2,
        'image' => 3
    ];

    const ONLY_READABLE_TYPES = [
        'system'
    ];

    public function supportChatRoom()
    {
        return $this->belongsTo(SupportChatRoom::class);
    }

    public function sender()
    {
        return $this->belongsTo(Owner::class, 'sender_id', 'id');
    }

    public function messagable()
    {
        return $this->morphTo();
    }
}
