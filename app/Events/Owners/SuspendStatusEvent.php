<?php

namespace App\Events\Owners;

use App\Components\Interfaces\ShouldReportEvent;
use App\Owner;
use App\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SuspendStatusEvent implements ShouldReportEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Owner
     */
    public $owner;

    /**
     * @var Ticket|null
     */
    public $ticket;

    /**
     * Create a new event instance.
     *
     * @param Owner $owner
     * @param Ticket|null $ticket
     */
    public function __construct(Owner $owner, Ticket $ticket)
    {
        $this->setOwner($owner);
        $this->setTicket($ticket);
    }

    public function getOwner (): Owner
    {
        return $this->owner;
    }

    public function setOwner (Owner $owner): Owner
    {
        $this->owner = $owner;
        return $this->owner;
    }

    public function getTicket (): Ticket
    {
        return $this->ticket;
    }

    public function setTicket (Ticket $ticket): Ticket
    {
        $this->ticket = $ticket;
        return $this->ticket;
    }
}
