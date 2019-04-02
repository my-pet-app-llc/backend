<?php

namespace App\Components\Classes\Chat;

use App\ChatRoom;

class Chat
{
    /**
     * Create new room
     *
     * @param int $recipientId
     * @return Room
     */
    public static function create(int $recipientId)
    {
        $pet = auth()->user()->pet;

        if(!($friend = $pet->findFriend($recipientId)))
            abort(404, 'Friend not found.');

        $friendsIds = $pet->chatRooms->pluck('pets')->collapse()->pluck('id')->toArray();

        if(array_search($recipientId, $friendsIds) !== false)
            abort(403, 'Chat with this friend already exist.');

        $room = ChatRoom::query()->create();
        $pet->chatRooms()->attach([$room->id], ['is_read' => true]);
        $friend->chatRooms()->attach([$room->id]);

        return new Room($room, $pet, $friend);
    }

    /**
     * Get existing room
     *
     * @param $room
     * @return Room
     */
    public static function get($room)
    {
        $room_id = $room;
        if($room instanceof ChatRoom)
            $room_id = $room->id;
        elseif(!is_int($room))
            abort(500, 'Argument room must be integer or ChatRoom instance.');

        $pet = auth()->user()->pet;
        $roomWithPets = $pet->chatRooms()->where('id', $room_id)->first();

        if(!$roomWithPets)
            abort(404, 'Room not found.');

        return new Room($roomWithPets, $pet, $roomWithPets->pets->first());
    }
}