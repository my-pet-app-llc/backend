<?php

namespace App\Http\Controllers\API;

use App\ChatMessage;
use App\Components\Classes\Chat\Chat;
use App\Exceptions\FriendshipException;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatPetResource;
use App\Http\Resources\ChatRoomResource;
use App\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/chats",
     *     tags={"Chat"},
     *     description="Get chat rooms",
     *     summary="Get chats",
     *     operationId="chatsGet",
     *     @OA\Response(
     *         response="200",
     *         description="List of chats",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Chat room ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="pet_id",
     *                     description="Pet(friend) ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="picture",
     *                     description="URl of profile picture collocutor pet",
     *                     example="http://mypets.com/storage/profile_picture/picture.png"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Name of collocutor pet",
     *                     example="Dog"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     description="Owner data of collocutor pet",
     *                     @OA\Property(
     *                         type="string",
     *                         property="first_name",
     *                         description="Collocutor pet owner first name",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="last_name",
     *                         description="Collocutor pet owner last name",
     *                         example="Doe"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="is_read",
     *                     description="1 or 0. Readed chat messages - 1, Not readed chat messages - 0",
     *                     example="0"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="last_message",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Message ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="type",
     *                         description="Message types: text, image, event",
     *                         example="text"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="message",
     *                         description="This message type 'text'. Refer to another sections of the documentation to find out what data comes in different types",
     *                         @OA\Property(
     *                             type="string",
     *                             property="message",
     *                             description="Displayed message",
     *                             example="Hello world!"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="date_time_created",
     *                         description="Date and time for message created.",
     *                         example="2019-04-04 16:50:59"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="like",
     *                         description="0 or 1. Liked - 1, Not liked - 0",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="sender",
     *                         description="Who send this message",
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Sender pet ID",
     *                             example="2"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="name",
     *                             description="Name of sender pet",
     *                             example="Cat"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="profile_picture",
     *                             description="URL of the sender pet profile picture",
     *                             example="http://mypets.com/storage/profile_picture/example.jpg"
     *                         ),
     *                         @OA\Property(
     *                             type="object",
     *                             property="owner",
     *                             description="Owner data for sender pet",
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="first_name",
     *                                 description="Sender pet owner first name",
     *                                 example="John"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="last_name",
     *                                 description="Sender pet owner last name",
     *                                 example="Doe"
     *                             ),
     *                         ),
     *                     ),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @return JsonResponse
     */
    public function chats()
    {
        $pet = auth()->user()->pet;
        $responseData = ChatRoomResource::collection($pet->chatRooms)->toArray(request());

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws FriendshipException
     */
    public function create(Request $request)
    {
        $friend = $request->get('pet_id');
        $chat = Chat::create($friend);

        return response()->json(['room_id' => $chat->getRoom()->id]);
    }

    /**
     * @OA\Get(
     *     path="/chats/{room_id}",
     *     tags={"Chat"},
     *     description="Messages in chat",
     *     summary="Chat messages",
     *     operationId="chatMessages",
     *     @OA\Parameter(
     *         name="room_id",
     *         description="Room ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="Page pagination. Count messages in page - 25",
     *         in="query",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Messages list",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Message ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="type",
     *                     description="Message types: text, image, event",
     *                     example="image"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="message",
     *                     description="This message type 'image'. Refer to another sections of the documentation to find out what data comes in different types",
     *                     @OA\Property(
     *                         type="string",
     *                         property="message",
     *                         description="URL for image message",
     *                         example="http://mypets.com/storage/pictures/example.com"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="date_time_created",
     *                     description="Date and time for message created.",
     *                     example="2019-04-04 16:50:59"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="like",
     *                     description="0 or 1. Liked - 1, Not liked - 0",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="sender",
     *                     description="Who send this message",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Sender pet ID",
     *                         example="2"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="name",
     *                         description="Name of sender pet",
     *                         example="Cat"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="profile_picture",
     *                         description="URL of the sender pet profile picture",
     *                         example="http://mypets.com/storage/profile_picture/example.jpg"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="owner",
     *                         description="Owner data for sender pet",
     *                         @OA\Property(
     *                             type="string",
     *                             property="first_name",
     *                             description="Sender pet owner first name",
     *                             example="John"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="last_name",
     *                             description="Sender pet owner last name",
     *                             example="Doe"
     *                         ),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="field",
     *                 @OA\Items(type="string", example="Invalid data")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Request $request
     * @param $room
     * @return JsonResponse
     * @throws ValidationException
     */
    public function roomMessages(Request $request, $room)
    {
        $this->validate($request, ['page' => 'nullable|integer|min:1']);

        $chat = Chat::get((int)$room);

        if($request->input('page', 1) == 1)
            $chat->updateRead();

        return response()->json($chat->getMessages());
    }

    /**
     * @OA\Post(
     *     path="/chats/{room_id}",
     *     tags={"Chat"},
     *     description="Send message for chat.",
     *     summary="Send message",
     *     operationId="chatSend",
     *     @OA\Parameter(
     *         name="room_id",
     *         description="Room ID. Send 0 if need to create a room",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="string",
     *                     property="type",
     *                     description="Type of message. Message types: text, image",
     *                     example="event"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="message",
     *                     description="Message for send",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="pet_id",
     *                     description="If need create a room, send pet ID",
     *                     example="1"
     *                 ),
     *                 required={"type", "message"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="New message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Message ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="room_id",
     *                 description="Room ID if room created",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type",
     *                 description="Message types: text, image, event",
     *                 example="event"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="message",
     *                 description="This message type 'event'. Refer to another sections of the documentation to find out what data comes in different types",
     *                 @OA\Property(
     *                     type="object",
     *                     property="message",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Invite ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="accepted",
     *                         description="Is accept or decline ivent. 0 - Decline, 1 - Accept, null - not accept and decline",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="type",
     *                         description="Social or care",
     *                         example="social"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="name",
     *                         description="Name of event",
     *                         example="First event"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="from_date",
     *                         description="Date for start event",
     *                         example="2019-04-08"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="from_time",
     *                         description="Time for start event",
     *                         example="12:30"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="to_time",
     *                         description="Time for end event",
     *                         example="13:30"
     *                     ),
     *                     @OA\Property(
     *                         type="array",
     *                         property="repeat",
     *                         description="Numbers days of week for repeat event",
     *                         @OA\Items(type="integer")
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="where",
     *                         description="Where will be event",
     *                         example="Park"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="notes",
     *                         description="Notes for event",
     *                         example="I'm stupid"
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="deleted_at",
     *                     description="If event has been deleted",
     *                     example="2019-04-04 18:06:15"
     *                 )
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="date_time_created",
     *                 description="Date and time for message created.",
     *                 example="2019-04-04 16:50:59"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="like",
     *                 description="0 or 1. Liked - 1, Not liked - 0",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="sender",
     *                 description="Who send this message",
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Sender pet ID",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Name of sender pet",
     *                     example="Cat"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="profile_picture",
     *                     description="URL of the sender pet profile picture",
     *                     example="http://mypets.com/storage/profile_picture/example.jpg"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     description="Owner data for sender pet",
     *                     @OA\Property(
     *                         type="string",
     *                         property="first_name",
     *                         description="Sender pet owner first name",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="last_name",
     *                         description="Sender pet owner last name",
     *                         example="Doe"
     *                     ),
     *                 ),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Cannot send message for this chat.|Cannot be direct create message of this type."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="field",
     *                 @OA\Items(type="string", example="Invalid data")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Request $request
     * @param $room
     * @return JsonResponse
     * @throws \Exception
     */
    public function send(Request $request, $room)
    {
        $message = $request->get('message');
        $type = $request->get('type');

        if(array_search($type, ChatMessage::DIRECT_TYPES) === false)
            abort(403, 'Cannot be direct create message of this type.');

        $roomId = (int)$room;
        if(!$roomId){
            if(!$request->get('pet_id'))
                return response()->json(['message' => 'Cannot send message for this chat.'], 403);

            $chat = Chat::create($request->get('pet_id'));
        }else{
            $chat = Chat::get($roomId);
        }

        try{
            $chatMessage = $chat->send($message, $type);
        }catch(\Exception $e){
            if($chat->isNew())
                $chat->destroy(false);

            throw $e;
        }

        $messageResource = (new ChatMessageResource($chatMessage))->toArray($request);
        if($chat->isNew())
            $messageResource['room_id'] = $chat->getRoom()->id;

        return response()->json($messageResource);
    }

    /**
     * @OA\Delete(
     *     path="/chats/{room_id}",
     *     tags={"Chat"},
     *     description="Delete chat",
     *     summary="Delete chat",
     *     operationId="chatDelete",
     *     @OA\Parameter(
     *         name="room_id",
     *         description="Room ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param $room
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($room)
    {
        $chat = Chat::get((int)$room);
        $chat->destroy();

        return response()->json(['message' => 'success']);
    }

    /**
     * @OA\Post(
     *     path="/chats/to/read",
     *     tags={"Chat"},
     *     description="Make chat read",
     *     summary="Read chat",
     *     operationId="chatToRead",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="room_id",
     *                     description="Room ID",
     *                     example="1"
     *                 ),
     *                 required={"room_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function read(Request $request)
    {
        $room = $request->get('room_id');

        $chat = Chat::get((int)$room);
        $chat->updateRead();

        return response()->json(['message' => 'success']);
    }

    /**
     * @OA\Get(
     *     path="/cahts/is/read",
     *     tags={"Chat"},
     *     description="Check readed chats",
     *     summary="Check readed chats",
     *     operationId="chatsIsRead",
     *     @OA\Response(
     *         response="200",
     *         description="Chats readed",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="boolean",
     *                 property="is_read",
     *                 description="true or false. If true - chats readed else chats not readed",
     *                 example=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @return JsonResponse
     */
    public function isRead()
    {
        $notReadRooms = auth()->user()->pet->chatRooms()->wherePivot('is_read', false)->count();
        return response()->json(['is_read' => !$notReadRooms]);
    }

    /**
     * @OA\Post(
     *     path="/chats/to/chat-search",
     *     tags={"Chat"},
     *     description="Search chats buy pet name",
     *     summary="Search chat",
     *     operationId="chtasSearch",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="string",
     *                     property="search",
     *                     description="Search by this field"
     *                 ),
     *                 required={"search"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of chats",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Chat room ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="picture",
     *                     description="URl of profile picture collocutor pet",
     *                     example="http://mypets.com/storage/profile_picture/picture.png"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Name of collocutor pet",
     *                     example="Dog"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     description="Owner data of collocutor pet",
     *                     @OA\Property(
     *                         type="string",
     *                         property="first_name",
     *                         description="Collocutor pet owner first name",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="last_name",
     *                         description="Collocutor pet owner last name",
     *                         example="Doe"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="is_read",
     *                     description="1 or 0. Readed chat messages - 1, Not readed chat messages - 0",
     *                     example="0"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="last_message",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Message ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="type",
     *                         description="Message types: text, image, event",
     *                         example="text"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="message",
     *                         description="This message type 'text'. Refer to another sections of the documentation to find out what data comes in different types",
     *                         @OA\Property(
     *                             type="string",
     *                             property="message",
     *                             description="Displayed message",
     *                             example="Hello world!"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="date_time_created",
     *                         description="Date and time for message created.",
     *                         example="2019-04-04 16:50:59"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="like",
     *                         description="0 or 1. Liked - 1, Not liked - 0",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="sender",
     *                         description="Who send this message",
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Sender pet ID",
     *                             example="2"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="name",
     *                             description="Name of sender pet",
     *                             example="Cat"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="profile_picture",
     *                             description="URL of the sender pet profile picture",
     *                             example="http://mypets.com/storage/profile_picture/example.jpg"
     *                         ),
     *                         @OA\Property(
     *                             type="object",
     *                             property="owner",
     *                             description="Owner data for sender pet",
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="first_name",
     *                                 description="Sender pet owner first name",
     *                                 example="John"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="last_name",
     *                                 description="Sender pet owner last name",
     *                                 example="Doe"
     *                             ),
     *                         ),
     *                     ),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchChat(Request $request)
    {
        $search = $request->get('search', '');
        $pet = $request->user()->pet;

        $rooms = $pet->chatRooms()->whereHas('pets', function($query) use ($search, $pet) {
            $query->where('id', '<>', $pet->id)->where('name', 'LIKE', "%{$search}%");
        })->get();

        $responseData = ChatRoomResource::collection($rooms)->toArray($request);
        return response()->json($responseData);
    }

    /**
     * @OA\Get(
     *     path="/chats/{room_id}/{messages_id}/like",
     *     tags={"Chat"},
     *     description="Like message in chat. Invert like value",
     *     summary="Like message",
     *     operationId="chatLikeMessage",
     *     @OA\Parameter(
     *         name="room_id",
     *         description="Room ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="message_id",
     *         description="Message ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param $room
     * @param $message
     * @return JsonResponse
     */
    public function likeMessage($room, $message)
    {
        $chat = Chat::get((int)$room);
        $chat->likeMessage((int)$message);

        return response()->json(['message' => 'success']);
    }

    /**
     * @OA\Get(
     *     path="/chats/pet/{pet_id}",
     *     tags={"Chat"},
     *     description="Get pet info for chat",
     *     summary="Get pet info",
     *     operationId="getPetInfo",
     *     @OA\Parameter(
     *         name="pet_id",
     *         description="Pet ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pet details for chat",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Sender pet ID",
     *                 example="2"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="name",
     *                 description="Name of sender pet",
     *                 example="Cat"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="profile_picture",
     *                 description="URL of the sender pet profile picture",
     *                 example="http://mypets.com/storage/profile_picture/example.jpg"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="owner",
     *                 description="Owner data for sender pet",
     *                 @OA\Property(
     *                     type="string",
     *                     property="first_name",
     *                     description="Sender pet owner first name",
     *                     example="John"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="last_name",
     *                     description="Sender pet owner last name",
     *                     example="Doe"
     *                 ),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="No query result from model."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Pet $pet
     * @return JsonResponse
     */
    public function pet($pet)
    {
        $pet = Pet::query()->findOrFail($pet);
        return response()->json(new ChatPetResource($pet));
    }
}
