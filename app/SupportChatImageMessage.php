<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportChatImageMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path'
    ];

    public function message()
    {
        return $this->morphOne(SupportChatMessage::class, 'messagable');
    }
}
