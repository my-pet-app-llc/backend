<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatPetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_picture' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $this->profile_picture,
            'owner' => [
                'first_name' => $this->owner->first_name,
                'last_name' => $this->owner->last_name
            ]
        ];
    }
}
