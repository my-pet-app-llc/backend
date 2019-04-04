<?php

namespace App\Components\Classes\Chat;

use App\ChatRoom;
use App\Pet;

class RoomsRepository
{
    /**
     * @var Pet
     */
    public $sender;

    /**
     * @var array|null
     */
    private $rooms;

    public function __construct(Pet $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Load rooms in repository
     *
     * @param $rooms
     * @return $this|null
     */
    public function load($rooms)
    {
        foreach ($rooms as $room) {
            if(!($room instanceof ChatRoom)){
                $this->rooms = null;
                return null;
            }

            $this->rooms[$room->id] = new Room($room, $this->sender, $room->pets->first());
        }

        return $this;
    }

    /**
     * Return room buy id
     *
     * @param int $id
     * @return Room|null
     */
    public function get(int $id)
    {
        if(isset($this->rooms[$id]))
            return $this->rooms[$id];
        else
            return null;
    }

    /**
     * Send message from all rooms
     *
     * @param $type
     * @param $message
     * @param bool $validate
     */
    public function send($type, $message, bool $validate = true)
    {
        foreach ($this->rooms as $room) {
            if($message instanceof \Closure)
                $messageData = $message($room);
            else
                $messageData = $message;

            $room->send($messageData, $type, $validate);
        }
    }
}