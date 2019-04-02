<?php

namespace App\Components\Classes\Chat;

use App\ChatImageMessage;
use App\ChatMessage;
use App\ChatRoom;
use App\ChatTextMessage;
use App\Components\Classes\StoreFile\File;
use App\Events\NewChatMessage;
use App\Events\NewEvent;
use App\Http\Resources\ChatMessageResource;
use App\Pet;
use Illuminate\Validation\ValidationException;
use Validator;

class Room
{
    /**
     * @var ChatRoom
     *
     * Room of chat
     */
    private $room;

    /**
     * Pet of the auth user
     *
     * @var Pet
     */
    private $sender;

    /**
     * Pet friend of the auth user
     *
     * @var Pet
     */
    private $recipient;

    /**
     * Room constructor.
     *
     * @param ChatRoom $room
     * @param Pet $sender
     * @param Pet $recipient
     */
    public function __construct(ChatRoom $room, Pet $sender, Pet $recipient)
    {
        $this->room = $room;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /**
     * Make message and broadcast message
     *
     * @param string $message
     * @param string $type
     * @return ChatMessage
     */
    public function send(string $message, string $type)
    {
        if(!isset(ChatMessage::TYPES[$type]))
            abort(404, 'Message type not found.');

        $action = 'make' . ucfirst(strtolower($type)) . 'Message';
        $chatMessage = $this->$action($message);

        $this->room->pets()->updateExistingPivot($this->recipient->id, ['is_read' => false]);

        $user = $this->recipient->owner->user;
        broadcast(new NewEvent($user, 'chat_message', [
            'room_id' => $this->room->id,
            'message' => new ChatMessageResource($chatMessage)
        ]));

        broadcast(new NewChatMessage($this->room, $chatMessage))->toOthers();

        return $chatMessage;
    }

    /**
     * Delete room
     *
     * @throws \Exception
     */
    public function destroy()
    {
        $roomId = $this->room->id;
        $this->room->delete();

        $user = $this->recipient->owner->user;
        broadcast(new NewEvent($user, 'chat_delete', [
            'room_id' => $roomId
        ]));
    }

    /**
     * Make all messages in this room for auth user is read
     *
     * @return void
     */
    public function updateRead()
    {
        $this->room->pets()->updateExistingPivot($this->sender->id, ['is_read' => true]);
    }

    /**
     * Get room of chat
     *
     * @return ChatRoom
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Get pet of the auth user
     *
     * @return Pet
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Get pet friend of the auth user
     *
     * @return Pet
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Get messages in room with pagination
     *
     * @param int $paginate
     * @return array
     */
    public function getMessages(int $paginate = 25)
    {
        $messages = $this->room->chatMessages()->latest()->paginate($paginate);
        $resource = ChatMessageResource::collection($messages)->toArray(request());

        return $resource;
    }

    /**
     * Trigger like message
     *
     * @param $messageId
     */
    public function likeMessage($messageId)
    {
        $message = $this->room->chatMessages()->findOrFail($messageId);
        $message->update(['is_like' => !$message->is_like]);
    }

    /**
     * Make text message
     *
     * @param $message
     * @return ChatMessage
     * @throws ValidationException
     */
    protected function makeTextMessage($message)
    {
        $type = 'text';
        $this->validateMessage($message, $type);

        $textMessage = ChatTextMessage::query()->create(['text' => $message]);

        return $this->createChatMessage($textMessage, $type);
    }

    /**
     * @param $message
     * @return ChatMessage
     * @throws ValidationException
     */
    protected function makeImageMessage($message)
    {
        $type = 'image';
        $this->validateMessage($message, $type);

        $file = new File($message);
        $file->validation(['jpg', 'png', 'jpeg']);
        $path = $file->store('chat');

        $imageMessage = ChatImageMessage::query()->create(['path' => $path]);

        return $this->createChatMessage($imageMessage, $type);
    }

    /**
     * @param $messageTypeModel
     * @param $type
     * @return ChatMessage
     */
    protected function createChatMessage($messageTypeModel, $type)
    {
        $chatMessage = new ChatMessage();
        $chatMessage->chat_room_id = $this->room->id;
        $chatMessage->sender_id = $this->sender->id;
        $chatMessage->type = ChatMessage::TYPES[$type];

        $messageTypeModel->message()->save($chatMessage);

        return $chatMessage;
    }

    /**
     * @param $message
     * @param $type
     * @throws ValidationException
     */
    protected function validateMessage($message, $type)
    {
        $action = 'get' . ucfirst(strtolower($type)) . 'Rules';
        $rules = $this->$action();

        $validator = Validator::make(['message' => $message], $rules);

        if($validator->fails())
            throw new ValidationException($validator);
    }

    /**
     * @return array
     */
    protected function getTextRules()
    {
        return [
            'message' => 'required|string|min:1,max:1000'
        ];
    }

    /**
     * @return array
     */
    protected function getImageRules()
    {
        return [
            'message' => ['required', 'string', 'regex:~^(data:image\/(jpeg|png|jpg);base64,\S+)$~']
        ];
    }
}