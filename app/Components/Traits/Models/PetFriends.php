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

    public function findFriends(array $friendsIds)
    {
        $friendsRel = $this->friends()->whereIn('friend_id', $friendsIds)->with('friend')->get();

        if(count($friendsIds) == $friendsRel->count())
            return $friendsRel->pluck('friend');
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

    /**
     * @param int $friendId
     * @return array
     */
    public function makeFriend(int $friendId)
    {
        $primary = Friend::query()->create([
            'pet_id' => $this->id,
            'friend_id' => $friendId
        ]);

        $secondary = Friend::query()->create([
            'pet_id' => $friendId,
            'friend_id' => $this->id
        ]);

        return compact('primary', 'secondary');
    }
}