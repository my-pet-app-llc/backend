<?php

namespace App\Components\Traits\Models;

use App\FriendRequest;
use Illuminate\Support\Collection;

trait OwnerRequests
{
    /**
     * @param $ownerId
     * @return Collection
     */
    public function getDeclinedOrNotRespondedRequestsWith ($ownerId)
    {
        return FriendRequest::query()
            ->where(function ($query) {
                return $query->whereNull('accept')->orWhere('accept', true);
            })
            ->where(function ($query) use ($ownerId) {
                return $query->where(function ($query) use ($ownerId) {
                        return $query->where('requesting_owner_id', $this->id)->orWhere('responding_owner_id', $ownerId);
                    })->orWhere(function ($query) use ($ownerId) {
                        return $query->where('responding_owner_id', $this->id)->orWhere('requesting_owner_id', $ownerId);
                    });
            })
            ->parseForUser($this->id)
            ->get();
    }

    public function ownFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'requesting_owner_id', 'id')->whereNull('accept');
    }

    public function friendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'responding_owner_id', 'id')->whereNull('accept');
    }
}