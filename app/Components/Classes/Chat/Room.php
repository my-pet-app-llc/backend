<?php

namespace App\Components\Classes\Chat;

use App\ChatEventMessage;
use App\ChatImageMessage;
use App\ChatMessage;
use App\ChatRoom;
use App\ChatTextMessage;
use App\Components\Classes\StoreFile\File;
use App\Events\DeleteChatEvent;
use App\Events\NewChatMessage;
use App\Events\ChatMessageEvent;
use App\Http\Resources\ChatMessageResource;
use App\Pet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
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
     * @param mixed $message
     * @param string $type
     * @param bool $validate
     * @return ChatMessage
     */
    public function send($message, string $type, bool $validate = true)
    {
        if(!isset(ChatMessage::TYPES[$type]))
            abort(404, 'Message type not found.');

        $chatMessage = $this->makeMessageHandler($message, $type, $validate);

        $this->room->pets()->updateExistingPivot($this->recipient->id, ['is_read' => false]);

        $user = $this->recipient->owner->user;
        broadcast(new ChatMessageEvent($user, [
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
        broadcast(new DeleteChatEvent($user, [
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
     * @param $message
     * @param $type
     * @param $validate
     * @return ChatMessage
     */
    private function makeMessageHandler($message, $type, $validate)
    {
        if($validate)
            $this->validateMessage($message, $type);

        $action = 'make' . ucfirst(strtolower($type)) . 'Message';
        $message = $this->$action($message);

        return $this->createChatMessage($message, $type);
    }

    /**
     * Make text message
     *
     * @param $message
     * @return Builder|Model
     */
    protected function makeTextMessage($message)
    {
        return ChatTextMessage::query()->create(['text' => $message]);
    }

    /**
     * @param $message
     * @return Builder|Model
     * @throws ValidationException
     */
    protected function makeImageMessage($message)
    {
        $file = new File($message);
        $file->validation(['jpg', 'png', 'jpeg']);
        $path = $file->store('chat');

        return ChatImageMessage::query()->create(['path' => $path]);
    }

    /**
     * @param $message
     * @return Builder|Model
     */
    public function makeEventMessage($message)
    {
        return ChatEventMessage::query()->create($message);
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
     * @param mixed $message
     * @param $type
     */
    protected function validateMessage($message, $type)
    {
        $action = 'get' . ucfirst(strtolower($type)) . 'Rules';
        $rules = $this->$action();

        $validator = Validator::make(['message' => $message], $rules);

        if($validator->fails())
            throw new HttpResponseException(response()->json($validator->errors(), 422));
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

    /**
     * @return array
     */
    public function getEventRules()
    {
        return [
            'message.event_id' => "required|exists:events,id,pet_id,{$this->sender->id}",
            'message.event_invite_id' => "required|exists:event_invites,id,pet_id,{$this->recipient->id}"
        ];
    }
}