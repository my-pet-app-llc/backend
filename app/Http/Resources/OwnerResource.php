<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    private $withPet;

    private $withPetPictures;

    public function __construct ($resource, $withPet = true, $withPetPictures = true)
    {
        $this->withPet = $withPet;
        $this->withPetPictures = $withPetPictures;

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'age' => $this->age,
            'birthday' => $this->birthday,
            'occupation' => $this->occupation,
            'hobbies' => $this->hobbies,
            'pets_owned' => $this->pets_owned,
            'profile_picture' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $this->profile_picture,
            'favorite_park' => $this->favorite_park
        ];

        if($this->withPet)
            $resourceArr['pet'] = (new PetResource($this->pet, $this->withPetPictures));

        return $resourceArr;
    }
}
