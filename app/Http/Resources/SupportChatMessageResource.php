<?php

namespace App\Http\Resources;

use App\SupportChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportChatMessageResource extends JsonResource
{
    const RESOURCES = [
        'system' => SupportChatSystemMessageResource::class,
        'text' => SupportChatTextMessageResource::class,
        'image' => SupportChatImageMessageResource::class
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = array_search($this->type, SupportChatMessage::TYPES);
        $resource = self::RESOURCES[$type];
        $message = new $resource($this->messagable);

        $user = auth()->user();
        $createdDate = $this->created_at;
        if($user->isPetOwner()){
            $utc = $user->owner->utc;
            if($utc > 0)
                $createdDate = $createdDate->addHours($utc);
            elseif($utc < 0)
                $createdDate = $createdDate->subHours($utc);
        }

        return [
            'id' => $this->id,
            'type' => $type,
            'message' => $message,
            'date_time_created' => $createdDate->toDateTimeString(),
            'is_like' => $this->is_like,
            'sender' => ($this->sender_id ? new OwnerResource($this->sender, true, false, false) : null)
        ];
    }
}
