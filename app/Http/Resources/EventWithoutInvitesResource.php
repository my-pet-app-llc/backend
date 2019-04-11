<?php

namespace App\Http\Resources;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventWithoutInvitesResource extends JsonResource
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
            'type' => array_search($this->type, Event::TYPES),
            'name' => $this->name,
            'from_date' => $this->from_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'repeat' => $this->repeat,
            'where' => $this->where,
            'notes' => $this->notes
        ];
    }
}
