<?php

namespace App\Components\Traits\Models;

use App\Friend;
use App\Pet;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PetFriends
{
    /**
     * @return HasMany
     */
    public function friends()
    {
        return $this->hasMany(Friend::class)->with(['friend']);
    }

    /**
     * @param int $friendId
     * @return Pet|null
     */
    public function findFriend(int $friendId)
    {
        $friendRel = $this->friends()->where('friend_id', $friendId)->first();

        if($friendRel)
            return $friendRel->friend;
        else
            return null;
    }

    /**
     * @param int $friendId
     * @return bool
     */
    public function hasFriend(int $friendId)
    {
        return (bool)$this->friends()->where('friend_id', $friendId)->first();
    }
}