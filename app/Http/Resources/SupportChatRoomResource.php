<?php

namespace App\Http\Resources;

use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportChatRoomResource extends JsonResource
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
            'ticket_no' => $this->ticket_id,
            'writable' => ($this->ticket->status != Ticket::STATUSES['resolved']),
            'last_message' => (new SupportChatMessageResource($this->supportChatMessages->last()))
        ];
    }
}
