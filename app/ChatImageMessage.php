<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatImageMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path'
    ];

    public function message()
    {
        return $this->morphOne(ChatMessage::class, 'messagable');
    }
}
