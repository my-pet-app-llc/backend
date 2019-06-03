<?php

namespace App\Components\Interfaces;

use App\Owner;
use App\Ticket;

interface ShouldReportEvent
{
    public function getOwner () : Owner;

    public function setOwner (Owner $owner) : Owner;

    public function getTicket () : Ticket;

    public function setTicket (Ticket $ticket) : Ticket;
}