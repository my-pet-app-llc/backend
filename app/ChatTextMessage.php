<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatTextMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'text'
    ];

    public function message()
    {
        return $this->morphOne(ChatMessage::class, 'messagable');
    }
}
