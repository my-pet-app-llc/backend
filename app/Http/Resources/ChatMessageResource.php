<?php

namespace App\Http\Resources;

use App\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    const RESOURCES = [
        'text' => ChatTextMessageResource::class,
        'image' => ChatImageMessageResource::class,
        'event' => ChatEventMessageResource::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = array_search($this->type, ChatMessage::TYPES);
        $resource = self::RESOURCES[$type];
        $message = new $resource($this->messagable);

        return [
            'id' => $this->id,
            'type' => $type,
            'message' => $message,
            'date_time_created' => $this->created_at->toDateTimeString(),
            'like' => $this->is_like,
            'sender' => (new ChatPetResource($this->sender)),
        ];
    }
}
