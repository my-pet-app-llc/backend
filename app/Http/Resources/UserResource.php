<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $withOwner;

    private $withPet;

    private $withPetPictures;

    public function __construct ($resource, $withOwner = true, $withPet = true, $withPetPictures = true)
    {
        $this->withOwner = $withOwner;
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
            'email' => $this->email
        ];

        if($this->withOwner)
            $resourceArr['owner'] = (new OwnerResource($this->owner, $this->withPet, $this->withPetPictures));

        return $resourceArr;
    }
}
