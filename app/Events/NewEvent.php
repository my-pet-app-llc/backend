<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;

    private $type;

    private $data;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $type
     * @param array $data
     */
    public function __construct(User $user, string $type, array $data = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channelName = 'new_event.' . $this->user->id;

        return new PrivateChannel($channelName);
    }

    public function broadcastAs()
    {
        return 'new.event';
    }

    public function broadcastWith()
    {
        return [
            'type' => $this->type,
            'data' => $this->data
        ];
    }
}
