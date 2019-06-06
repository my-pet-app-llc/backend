<?php

namespace App\Observers;

use App\Friend;
use App\FriendRequest;
use App\Jobs\UserForceDelete;
use App\User;
use Carbon\Carbon;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleting" event.
     *
     * @param User $user
     * @return void
     */
    public function deleting(User $user)
    {
        if($user->isPetOwner()){
            $user->owner->pet->chatRooms()->delete();
            $user->owner->requestedMatches()->delete();
            $user->owner->respondedMatches()->delete();
            foreach ($user->owner->pet->events as $event) {
                $event->eventInvites()->delete();
            }
            $user->owner->pet->events()->delete();
            $user->owner->pet->eventInvites()->delete();
            $user->owner->pet->friends()->delete();
            Friend::query()->where('friend_id', $user->owner->pet->id)->delete();
            FriendRequest::query()->where('requesting_owner_id', $user->owner->id)->orWhere('responding_owner_id', $user->owner->id)->delete();
            foreach ($user->owner->tickets as $ticket) {
                $ticket->supportChats()->delete();
            }
            $user->owner->tickets()->delete();
            foreach ($user->owner->reports as $report) {
                $report->supportChats()->delete();
            }
            $user->owner->reports()->delete();
            $user->owner->pet->delete();
            $user->owner->delete();
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        if($user->isPetOwner()){
            dispatch(new UserForceDelete($user->id))->delay(Carbon::now()->addDays(30));
        }
    }

    /**
     * Handle the user "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
