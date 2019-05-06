<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $fillable = [
        'requesting_owner_id', 'responding_owner_id', 'accept'
    ];

    public function requested ()
    {
        return $this->belongsTo(Owner::class, 'requesting_owner_id', 'id');
    }

    public function responded ()
    {
        return $this->belongsTo(Owner::class, 'responding_owner_id', 'id');
    }

    public function scopeParseForUser ($query, $ownerId)
    {
        return $query
            ->selectRaw('IF(requesting_owner_id = ?, responding_owner_id, requesting_owner_id) as owner_id', [$ownerId])
            ->selectRaw('IF(requesting_owner_id = ?, 1, 0) as creator', [$ownerId])
            ->selectRaw('accept');
    }
}
