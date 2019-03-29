<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
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
            'like' => $this->like,
            'dislike' => $this->dislike,
            'favorite_toys' => $this->favorite_toys,
            'fears' => $this->fears,
            'favorite_places' => $this->favorite_places,
            'spayed' => $this->spayed,
            'birthday' => $this->birthday,
            'favorite_park' => $this->favorite_park,
            'pictures' => PetPicturesResource::collection($this->pictures)
        ];
    }
}
