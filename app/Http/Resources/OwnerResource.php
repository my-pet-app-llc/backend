<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'age' => $this->age,
            'birthday' => $this->birthday,
            'occupation' => $this->occupation,
            'hobbies' => $this->hobbies,
            'pets_owned' => $this->pets_owned,
            'city' => $this->city,
            'state' => $this->state,
            'profile_picture' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $this->profile_picture,
            'pet' => (new PetResource($this->pet))
        ];
    }
}
