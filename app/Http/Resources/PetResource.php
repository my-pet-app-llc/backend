<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    private $withPetPictures;

    private $withFriends;

    public function __construct ($resource, $withPetPictures = true, $withFriends = false)
    {
        $this->withPetPictures = $withPetPictures;
        $this->withFriends = $withFriends;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resourceArr = [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'size' => $this->size,
            'primary_breed' => $this->primary_breed,
            'secondary_breed' => $this->secondary_breed,
            'age' => $this->age,
            'profile_picture' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $this->profile_picture,
            'friendliness' => $this->friendliness,
            'activity_level' => $this->activity_level,
            'noise_level' => $this->noise_level,
            'odebience_level' => $this->odebience_level,
            'fetchability' => $this->fetchability,
            'swimability' => $this->swimability,
            'city' => $this->city,
            'state' => $this->state,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'favorite_toys' => $this->favorite_toys,
            'fears' => $this->fears,
            'favorite_places' => $this->favorite_places,
            'spayed' => $this->spayed,
            'birthday' => $this->birthday
        ];

        if($this->withPetPictures)
            $resourceArr['pictures'] = PetPicturesResource::collection($this->pictures);

        if($this->withFriends)
            $resourceArr['friends'] = FriendResource::collection($this->friends);

        return $resourceArr;
    }
}
