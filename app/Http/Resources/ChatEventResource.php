<?php

namespace App\Http\Resources;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $authUser = auth()->user();
        if(optional($authUser)->owner){
            $utc = $authUser->owner->utc;
            $this->convertDateTimeAttributesToTimezone($utc);
        }

        return [
            'type' => array_search($this->type, Event::TYPES),
            'name' => $this->name,
            'from_date' => $this->from_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'repeat' => $this->repeat,
            'where' => $this->where,
            'notes' => $this->notes,
        ];
    }
}
