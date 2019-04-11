<?php

namespace App;

use App\Components\Traits\Models\PetEvents;
use App\Components\Traits\Models\PetFriends;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use PetFriends, PetEvents;

    public $fillable = [
        'owner_id', 'name', 'gender',
        'size', 'primary_breed', 'secondary_breed',
        'age', 'profile_picture', 'friendliness',
        'activity_level', 'noise_level', 'odebience_level',
        'fetchability', 'swimability', 'like',
        'dislike', 'favorite_toys', 'fears',
        'favorite_places', 'spayed', 'birthday',
        'city', 'state',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function pictures()
    {
        return $this->morphMany(Picture::class, 'picturable');
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'pet_chat_room')
            ->withPivot(['is_read'])
            ->with(['pets' => function ($query) {
                return $query->where('id', '<>', $this->id);
            }]);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function eventInvites()
    {
        return $this->hasMany(EventInvite::class);
    }

    public function invitedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_invites');
    }
}
