<?php

namespace App\Listeners\Owners;

use App\Components\Interfaces\ShouldReportEvent;
use App\Notifications\API\UserSuspended;
use App\Owner;
use App\Ticket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSuspendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ShouldReportEvent $event
     * @return void|string
     */
    public function handle(ShouldReportEvent $event)
    {
        $ticket = $event->getTicket();
        $owner = $event->getOwner();

        try{
            $owner->user->notify(new UserSuspended($ticket));
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
