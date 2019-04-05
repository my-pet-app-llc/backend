<?php

namespace App\Events;

use App\ChatMessage;
use App\ChatRoom;
use App\Http\Resources\ChatMessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $room;

    private $message;

    /**
     * Create a new event instance.
     *
     * @param ChatRoom $room
     * @param ChatMessage $message
     */
    public function __construct(ChatRoom $room, ChatMessage $message)
    {
        $this->room = $room;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channelName = 'chat.' . $this->room->id;

        return new PrivateChannel($channelName);
    }

    public function broadcastAs()
    {
        return 'chat.event';
    }

    public function broadcastWith()
    {
        return (new ChatMessageResource($this->message))->toArray(request());
    }
}
