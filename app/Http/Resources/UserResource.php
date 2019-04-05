<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $withOwner;

    private $withPet;

    private $withPetPictures;

    private $withFriends;

    public function __construct ($resource, $withOwner = true, $withPet = true, $withPetPictures = true, $withFriends = false)
    {
        $this->withOwner = $withOwner;
        $this->withPet = $withPet;
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
            'email' => $this->email
        ];

        if($this->withOwner)
            $resourceArr['owner'] = (new OwnerResource($this->owner, $this->withPet, $this->withPetPictures, $this->withFriends));

        return $resourceArr;
    }
}
