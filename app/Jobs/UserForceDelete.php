<?php

namespace App\Jobs;

use App\Friend;
use App\FriendRequest;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;

class UserForceDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->where('id', $this->userId)->withTrashed()->first();
        if($user && $user->trashed()){
            $owner = $user->owner()->withTrashed()->first();
            $pet = $owner->pet()->withTrashed()->first();

            $chats = $pet->chatRooms()->withTrashed()->get();
            foreach ($chats as $chat) {
                foreach ($chat->chatMessages as $chatMessage) {
                    $chatMessage->messagable()->delete();
                }
            }
            $pet->chatRooms()->withTrashed()->forceDelete();
            $owner->requestedMatches()->withTrashed()->forceDelete();
            $owner->respondedMatches()->withTrashed()->forceDelete();
            
            $events = $pet->events()->withTrashed()->get();
            foreach ($events as $event) {
                $event->eventInvites()->forceDelete();
            }
            $pet->events()->withTrashed()->forceDelete();
            $pet->eventInvites()->withTrashed()->forceDelete();
            $pet->friends()->withTrashed()->forceDelete();
            Friend::query()->where('friend_id', $pet->id)->withTrashed()->forceDelete();
            FriendRequest::query()->where('requesting_owner_id', $owner->id)->orWhere('responding_owner_id', $owner->id)->withTrashed()->forceDelete();

            $tickets = [$owner->tickets()->withTrashed()->get(), $owner->reports()->withTrashed()->get()];
            foreach ($tickets as $ticketType) {
                foreach ($ticketType as $ticket) {
                    $supportChats = $ticket->supportChats()->withTrashed()->get();
                    foreach ($supportChats as $supportChat) {
                        foreach ($supportChat->supportChatMessages as $supportChatMessage) {
                            $supportChatMessage->messagable()->delete();
                        }
                    }
                    $ticket->supportChats()->withTrashed()->forceDelete();
                }
            }
            $owner->tickets()->withTrashed()->forceDelete();
            $owner->reports()->withTrashed()->forceDelete();

            $pictures = $pet->pictures;
            foreach ($pictures as $picture) {
                Storage::disk('public')->delete($picture->picture);
            }

            $pet->pictures()->delete();
            $pet->forceDelete();
            $owner->forceDelete();
            $user->forceDelete();
        }
    }
}
