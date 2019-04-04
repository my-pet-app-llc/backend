<?php

namespace App\Http\Resources;

use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatEventMessageResource extends JsonResource
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
            'message' => $this->deleted_at ? null : new ChatEventInviteResource($this->eventInvite),
            'deleted_at' => $this->deleted_at
        ];
    }
}
