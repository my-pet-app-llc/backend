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

        return [
            'id' => $this->id,
            'type' => $type,
            'message' => $message,
            'date_time_created' => $this->created_at->toDateTimeString(),
            'is_like' => $this->is_like,
            'sender' => ($this->sender_id ? new OwnerResource($this->sender, true, false, false) : null)
        ];
    }
}
