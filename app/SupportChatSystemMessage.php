<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportChatSystemMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'text'
    ];

    public function message()
    {
        return $this->morphOne(SupportChatMessage::class, 'messagable');
    }
}
