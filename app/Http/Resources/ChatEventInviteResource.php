<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatEventInviteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $eventInviteArray = [
            'id' => $this->id,
            'accepted' => $this->accepted
        ];

        $eventArray = (new ChatEventResource($this->event))->toArray($request);

        return array_merge($eventInviteArray, $eventArray);
    }
}
