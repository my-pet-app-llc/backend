<?php

namespace App\Observers;

use App\Events\Owners\SuspendStatusEvent;
use App\Owner;

class OwnerObserver
{
    public function updating (Owner $owner)
    {
        $dirtyAttrs = $owner->getDirty();

        if(array_key_exists('status', $dirtyAttrs) && $dirtyAttrs['status'] == Owner::STATUS['suspended']){
            $response = event(new SuspendStatusEvent($owner, $owner->suspendedTicket));
            foreach ($response as $listenerResponse) {
                if($listenerResponse)
                    return false;
            }
        }
    }
}
