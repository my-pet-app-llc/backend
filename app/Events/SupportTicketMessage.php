<?php

namespace App\Events;

use App\Http\Resources\SupportChatMessageResource;
use App\SupportChatMessage;
use App\SupportChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;

class SupportTicketMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $room;

    private $message;

    private $resource;

    /**
     * Create a new event instance.
     *
     * @param SupportChatRoom $room
     * @param SupportChatMessage $message
     */
    public function __construct(SupportChatRoom $room, SupportChatMessage $message)
    {
        $this->room = $room;
        $this->message = $message;

        $this->resource = (new SupportChatMessageResource($this->message))->toArray(request());
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('ticket.chat.' . $this->room->id);
    }

    public function broadcastAs()
    {
        return 'ticket.chat.message';
    }

    public function broadcastWith()
    {
        return $this->resource;
    }
}
