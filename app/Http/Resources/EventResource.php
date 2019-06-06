<?php

namespace App\Http\Resources;

use App\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $pets =  $this->eventInvites()->with('pet')->where(function ($q){
            return $q->whereNull('accepted')->orWhere('accepted', true);
        })->get()->pluck('pet');

        $authUser = auth()->user();
        if(optional($authUser)->owner){
            $utc = $authUser->owner->utc;
            $this->convertDateTimeAttributesToTimezone($utc);
        }

        return [
            'id' => $this->id,
            'type' => array_search($this->type, Event::TYPES),
            'name' => $this->name,
            'from_date' => $this->from_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'repeat' => $this->repeat,
            'where' => $this->where,
            'notes' => $this->notes,
            'invited' => PetResource::collection($pets)
        ];
    }
}
