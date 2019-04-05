<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $last_message = $this->chatMessages->last();
        $friend = $this->pets->where('id', '!=', $request->user()->id)->first();
        $picture = $friend->profile_picture;
        $name = $friend->name;
        $owner = [
            'first_name' => $friend->owner->first_name,
            'last_name' => $friend->owner->last_name
        ];

        return [
            'id' => $this->id,
            'picture' => config('filesystems.disks')[env('FILESYSTEM_DRIVER', 'public')]['url'] . $picture,
            'name' => $name,
            'owner' => $owner,
            'is_read' => $this->pivot->is_read,
            'last_message' => $last_message ? (new ChatMessageResource($last_message)) : null
        ];
    }
}
