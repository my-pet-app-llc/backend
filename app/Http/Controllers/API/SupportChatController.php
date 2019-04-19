<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\StoreFile\File;
use App\Events\SupportTicketMessage;
use App\Http\Resources\SupportChatMessageResource;
use App\Http\Resources\SupportChatRoomResource;
use App\Owner;
use App\SupportChatImageMessage;
use App\SupportChatMessage;
use App\SupportChatRoom;
use App\SupportChatSystemMessage;
use App\SupportChatTextMessage;
use App\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class SupportChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/support-chats",
     *     tags={"Support"},
     *     description="Get all chats for support",
     *     summary="Get chats",
     *     operationId="getSupportChats",
     *     @OA\Response(
     *         response="200",
     *         description="All chats",
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
     *                     property="ticket_no",
     *                     description="Ticket ID as chat name",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="boolean",
     *                     property="writable",
     *                     description="The ability to write in chat",
     *                     example=true
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="is_read",
     *                     description="Are there any unread messages in the chat",
     *                     example="0"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="last_message",
     *                     description="Last message in chat",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Message ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="type",
     *                         description="Message type. Can be system, text and image",
     *                         example="text"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="message",
     *                         description="Message object. For all message types has one property. For system and text type just text. For image type - path to image",
     *                         @OA\Property(
     *                             type="string",
     *                             property="message",
     *                             description="Message text or path to image",
     *                             example="Hello World!"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="date_time_created",
     *                         description="Date and time message created",
     *                         example="2019-04-18 10:00:00"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="is_like",
     *                         description="Like message",
     *                         example="0"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="sender",
     *                         description="Owner object or null if message sended admin",
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Owner ID",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="first_name",
     *                             description="Owner first name",
     *                             example="John"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="last_name",
     *                             description="Owner last name",
     *                             example="Doe"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="gender",
     *                             description="Owner gender",
     *                             example="male"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="age",
     *                             description="Owner age",
     *                             example="12"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="birthday",
     *                             description="Owner birthday",
     *                             example="1298-04-12"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="occupation",
     *                             description="Owner occupation",
     *                             example="Developer"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="hobbies",
     *                             description="Owner hobbies",
     *                             example="Bikes"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="pets_owned",
     *                             description="Owner pets owned",
     *                             example="33 cows"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="profile_picture",
     *                             description="Owner profile picture URL",
     *                             example="http://mypets.com/storage/profile_picture/example.png"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="favorite_park",
     *                             description="Owner favorite park",
     *                             example="Park"
     *                         ),
     *                         @OA\Property(
     *                             type="object",
     *                             property="pet",
     *                             description="Owner pet",
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="id",
     *                                 description="Pet ID",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="name",
     *                                 description="Pet name",
     *                                 example="Dog"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="gender",
     *                                 description="Pet gender",
     *                                 example="male"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="size",
     *                                 description="Pet size",
     *                                 example="giant"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="primary_breed",
     *                                 description="Pet primary breed",
     *                                 example="Dog"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="secondary_breed",
     *                                 description="Pet secondary breed",
     *                                 example="Cat"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="age",
     *                                 description="Pet age",
     *                                 example="12"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="profile_picture",
     *                                 description="Pet profile picture URL",
     *                                 example="http://mypets.com/storage/profile_picture/example.png"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="friendliness",
     *                                 description="Pet friendliness",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="activity_level",
     *                                 description="Pet activity level",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="noise_level",
     *                                 description="Pet noise level",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="odebience_level",
     *                                 description="Pet odebience level",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="fetchability",
     *                                 description="Pet fetchbility",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="integer",
     *                                 property="swimability",
     *                                 description="Pet swimability",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="city",
     *                                 description="Pet city",
     *                                 example="New York"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="state",
     *                                 description="Pet state",
     *                                 example="NY"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="like",
     *                                 description="Pet likes",
     *                                 example="Apples"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="dislike",
     *                                 description="Pet dislikes",
     *                                 example="Bananas"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="favorite_toys",
     *                                 description="Pet favorite toys",
     *                                 example="Foot"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="fears",
     *                                 description="Pet fears",
     *                                 example="Chicken"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="favorite_places",
     *                                 description="Pet favorite places",
     *                                 example="Park"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="spayed",
     *                                 description="Pet spayed. 1 or 0",
     *                                 example="1"
     *                             ),
     *                             @OA\Property(
     *                                 type="string",
     *                                 property="birthday",
     *                                 description="Pet birthday",
     *                                 example="1939-09-01"
     *                             ),
     *                         ),
     *                     )
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
    public function getRooms()
    {
        $rooms = auth()
            ->user()
            ->owner
            ->supportChatRooms()
            ->with([
                'ticket',
                'supportChatMessages.messagable',
                'supportChatMessages.sender.pet'
            ])
            ->get();

        return response()->json(SupportChatRoomResource::collection($rooms));
    }

    /**
     * @OA\Get(
     *     path="/support-chats/{room_id}",
     *     tags={"Support"},
     *     description="Get messages in chat with pagination",
     *     summary="Get messages in chat",
     *     operationId="getMessagesSupportChat",
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
     *                     description="Message type. Can be system, text or image",
     *                     example="image"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="message",
     *                     description="Message object. For all message types has one property. For system and text type just text. For image type - path to image",
     *                     @OA\Property(
     *                         type="string",
     *                         property="message",
     *                         description="Message text or path to image",
     *                         example="http://mypets.com/storage/support-chat/example.jpeg"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="date_time_created",
     *                     description="Date and time message created",
     *                     example="2019-04-17 10:00:00"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="is_like",
     *                     description="Like message. 1 or 0",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="sender",
     *                     description="Owner object or null if message sended admin",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Owner ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="first_name",
     *                         description="Owner first name",
     *                         example="John"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="last_name",
     *                         description="Owner last name",
     *                         example="Doe"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="gender",
     *                         description="Owner gender",
     *                         example="male"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="age",
     *                         description="Owner age",
     *                         example="12"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="birthday",
     *                         description="Owner birthday",
     *                         example="1298-04-12"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="occupation",
     *                         description="Owner occupation",
     *                         example="Developer"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="hobbies",
     *                         description="Owner hobbies",
     *                         example="Bikes"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="pets_owned",
     *                         description="Owner pets owned",
     *                         example="33 cows"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="profile_picture",
     *                         description="Owner profile picture URL",
     *                         example="http://mypets.com/storage/profile_picture/example.png"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="favorite_park",
     *                         description="Owner favorite park",
     *                         example="Park"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="pet",
     *                         description="Owner pet",
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Pet ID",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="name",
     *                             description="Pet name",
     *                             example="Dog"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="gender",
     *                             description="Pet gender",
     *                             example="male"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="size",
     *                             description="Pet size",
     *                             example="giant"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="primary_breed",
     *                             description="Pet primary breed",
     *                             example="Dog"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="secondary_breed",
     *                             description="Pet secondary breed",
     *                             example="Cat"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="age",
     *                             description="Pet age",
     *                             example="12"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="profile_picture",
     *                             description="Pet profile picture URL",
     *                             example="http://mypets.com/storage/profile_picture/example.png"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="friendliness",
     *                             description="Pet friendliness",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="activity_level",
     *                             description="Pet activity level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="noise_level",
     *                             description="Pet noise level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="odebience_level",
     *                             description="Pet odebience level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="fetchability",
     *                             description="Pet fetchbility",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="integer",
     *                             property="swimability",
     *                             description="Pet swimability",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="city",
     *                             description="Pet city",
     *                             example="New York"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="state",
     *                             description="Pet state",
     *                             example="NY"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="like",
     *                             description="Pet likes",
     *                             example="Apples"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="dislike",
     *                             description="Pet dislikes",
     *                             example="Bananas"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="favorite_toys",
     *                             description="Pet favorite toys",
     *                             example="Foot"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="fears",
     *                             description="Pet fears",
     *                             example="Chicken"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="favorite_places",
     *                             description="Pet favorite places",
     *                             example="Park"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="spayed",
     *                             description="Pet spayed. 1 or 0",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="birthday",
     *                             description="Pet birthday",
     *                             example="1939-09-01"
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
     *     @OA\Response(
     *         response="404",
     *         description="Not found error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Room not found.|Np query results for model"
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param SupportChatRoom $room
     * @return JsonResponse
     */
    public function getRoomMessages(SupportChatRoom $room)
    {
        $owner = auth()->user()->owner;

        if($room->owner_id != $owner->id)
            return response()->json(['message' => 'Room not found.'], 404);

        $messages = $room->supportChatMessages()->with(['messagable', 'sender.pet'])->paginate(25);

        return response()->json(SupportChatMessageResource::collection($messages));
    }

    /**
     * @OA\Get(
     *     path="/support-chats/messages/default",
     *     tags={"Support"},
     *     description="Get default message for new chat",
     *     summary="Get default message",
     *     operationId="getSupportChatDefaultMessage",
     *     @OA\Response(
     *         response="200",
     *         description="Default message for new chat",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 description="Default system message",
     *                 example="Hi, Thank you for using MyPet! How can we help you today?"
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
    public function getDefaultMessage()
    {
        return response()->json(['message' => $this->defaultMessage()]);
    }

    /**
     * @OA\Post(
     *     path="/support-chats/{ticket_id}",
     *     tags={"Support"},
     *     description="Send message for chat",
     *     summary="Send message",
     *     operationId="sendMessageSupportChat",
     *     @OA\Parameter(
     *         name="ticket_id",
     *         description="Ticket ID. Send '0' if need create ticket",
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
     *                     description="Type of message. Can be text or image",
     *                     example="text"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="message",
     *                     description="Message. If type text - just text, if image - correct base64 string",
     *                     example="Hello World!"
     *                 ),
     *                 required={"type", "message"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Message ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type",
     *                 description="Message type. Can be system, text or image",
     *                 example="text"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="message",
     *                 description="Message object. For all message types has one property. For system and text type just text. For image type - path to image",
     *                 @OA\Property(
     *                     type="string",
     *                     property="message",
     *                     description="Message text or path to image",
     *                     example="Hello World!"
     *                 )
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="date_time_created",
     *                 description="Date and time message created",
     *                 example="2019-04-19 20:00:00"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="is_like",
     *                 description="Like message. 1 or 0",
     *                 example="0"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="sender",
     *                 description="Sender object or null if message sended admin",
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Owner ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="first_name",
     *                     description="Owner first name",
     *                     example="John"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="last_name",
     *                     description="Owner last name",
     *                     example="Doe"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="gender",
     *                     description="Owner gender",
     *                     example="male"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="age",
     *                     description="Owner age",
     *                     example="11"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="birthday",
     *                     description="Owner birthday",
     *                     example="1970-01-01"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="occupation",
     *                     description="Owner occupation",
     *                     example="Developer"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="hobbies",
     *                     description="Owner hobbies",
     *                     example="Kill"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="pets_owned",
     *                     description="Owner pets owned",
     *                     example="33 cows"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="profile_picture",
     *                     description="Owner profile picture URL",
     *                     example="http://mypets.com/storage/profile_picture/example.png"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="favorite_park",
     *                     description="Owne favorite park",
     *                     example="Park"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="pet",
     *                     description="Pet object",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Pet ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="name",
     *                         description="Pet name",
     *                         example="Muhtar"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="gender",
     *                         description="Pet gender",
     *                         example="male"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="size",
     *                         description="Pet size",
     *                         example="giant"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="primary_breed",
     *                         description="Pet primary breed",
     *                         example="Dog"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="secondary_breed",
     *                         description="Pet secodary breed",
     *                         example="Cat"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="age",
     *                         description="Pet age",
     *                         example="13"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="profile_picture",
     *                         description="Pet profile picture URL",
     *                         example="http://mypets.com/storage/profile_picture/example.com"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="friendliness",
     *                         description="Pet friendliness",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="activity_level",
     *                         description="Pet activity level",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="noise_level",
     *                         description="Pet noise level",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="odebience_level",
     *                         description="Pet odebience level",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="fetchability",
     *                         description="Pet fetchability",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="swimability",
     *                         description="Pet swimability",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="city",
     *                         description="Pet city",
     *                         example="New York"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="state",
     *                         description="Pet state",
     *                         example="NY"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="like",
     *                         description="Pet likes",
     *                         example="Apples"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="dislike",
     *                         description="Pet dislikes",
     *                         example="Bananas"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="favorite_toys",
     *                         description="Pet favorite toys",
     *                         example="God"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="fears",
     *                         description="Pet fears",
     *                         example="Foot"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="favorite_places",
     *                         description="Pet favorite places",
     *                         example="Land"
     *                     ),
     *                     @OA\Property(
     *                         type="integer",
     *                         property="spayed",
     *                         description="Pet spayed. 1 or 0",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="birthday",
     *                         description="Pet birthday",
     *                         example="1939-09-01"
     *                     )
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
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Ticket already resolved."
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
     *                 example="Message type not found.|Support chat not found.|No query results for model"
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
     * @param $ticket
     * @return JsonResponse
     * @throws ValidationException
     */
    public function send(Request $request, $ticket)
    {
        $this->validate($request, ['message' => 'required|string|min:1']);

        $owner = auth()->user()->owner;
        $messageType = $request->get('type');
        $existType = array_key_exists($messageType, SupportChatMessage::TYPES);

        if(!$existType || array_search($messageType, SupportChatMessage::ONLY_READABLE_TYPES) !== false)
            return response()->json(['message' => 'Message type not found.'], 404);

        if($ticket == 0){
            $supportTicket = $owner->tickets()->save(new Ticket(['status' => Ticket::STATUSES['new']]));
            $supportRoom = $owner->supportChatRooms()->save(new SupportChatRoom(['ticket_id' => $supportTicket->id, 'is_read' => true]));
            $systemMessage = SupportChatSystemMessage::query()->create([
                'text' => $this->defaultMessage()
            ]);
            $systemMessage->message()->save(new SupportChatMessage([
                'support_chat_room_id' => $supportRoom->id,
                'type' => SupportChatMessage::TYPES['system']
            ]));
        }else{
            $supportTicket = Ticket::with(['supportChats'])->findOrFail($ticket);
            $supportRoom = $supportTicket->supportChats->where('owner_id', $owner->id)->first();

            if(!$supportRoom)
                return response()->json(['message' => 'Support chat not found.'], 404);

            if($supportTicket->status == Ticket::STATUSES['resolved'])
                return response()->json(['message' => 'Ticket already resolved.'], 403);
        }

        $messageTypeModel = null;
        $message = $request->get('message');
        if($messageType == 'text'){
            $messageTypeModel = SupportChatTextMessage::query()->create([
                'text' => $request->get('message')
            ]);
        }elseif($messageType == 'image'){
            $file = new File($message);
            $file->validation(['jpg', 'png', 'jpeg']);
            $path = $file->store('support-chat');
            $messageTypeModel = SupportChatImageMessage::query()->create([
                'path' => $path
            ]);
        }

        $messageModel = $messageTypeModel->message()->save(new SupportChatMessage([
            'support_chat_room_id' => $supportRoom->id,
            'type' => SupportChatMessage::TYPES[$messageType],
            'sender_id' => $owner->id
        ]));

        broadcast(new SupportTicketMessage($supportRoom, $messageModel))->toOthers();

        $supportRoom->update(['is_read' => true]);

        $newStatus = Owner::STATUS['in_progres'];
        if($owner->status == Owner::STATUS['reported'] || $owner->status == Owner::STATUS['reporting'])
            $newStatus = Owner::STATUS['reporting'];

        if($owner->status != $newStatus)
            $owner->update(['status' => $newStatus]);

        $messageResource = (new SupportChatMessageResource($messageModel))->toArray($request);
        if(!$ticket)
            $messageResource['room_id'] = $supportRoom->id;

        return response()->json($messageResource);
    }

    /**
     * @OA\Post(
     *     path="/support-chats/to/read",
     *     tags={"Support"},
     *     description="Read messages in chat room",
     *     summary="Read messages in chat room",
     *     operationId="readMessagesSupportRoom",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="ticket_id",
     *                     description="Ticket ID",
     *                     example="1"
     *                 ),
     *                 required={"ticket_id"}
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
     *                 example="Chat room not found.|No query results for model"
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
        $owner = auth()->user()->owner;
        $room = Ticket::query()->findOrFail($request->get('ticket_id'))->supportChats()->where('owner_id', $owner->id)->first();

        if(!$room)
            return response()->json(['message' => 'Chat room not found.'], 404);

        $room->update(['is_read' => true]);
        return response()->json(['message' => 'success']);
    }

    /**
     * @OA\Get(
     *     path="/support-chats/is/read",
     *     tags={"Support"},
     *     description="Are there chats not read",
     *     summary="Check unread chats",
     *     operationId="isReadSupportChats",
     *     @OA\Response(
     *         response="200",
     *         description="Have all chats been read",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="boolean",
     *                 property="is_read",
     *                 description="Have all chats been read",
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
        $notReadRooms = auth()->user()->owner->supportChatRooms()->where('is_read', false)->count();
        return response()->json(['is_read' => !$notReadRooms]);
    }

    /**
     * @OA\Post(
     *     path="/support-chats/to/like/{ticket_id}/{message_id}",
     *     tags={"Support"},
     *     description="Like message",
     *     summary="Like message",
     *     operationId="likeSupportChatMessage",
     *     @OA\Parameter(
     *         name="ticket_id",
     *         description="Ticket ID",
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
     *                 example="Chat room not found.|No query results for model"
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @param Ticket $ticket
     * @param $message
     * @return JsonResponse
     */
    public function like(Ticket $ticket, $message)
    {
        $owner = auth()->user()->owner;
        $room = $ticket->supportChats()->where('owner_id', $owner->id)->first();

        if(!$room)
            return response()->json(['message' => 'Chat room not found.'], 404);

        $roomMessage = $room->supportChatMessages()->findOrFail($message);
        $roomMessage->update(['is_like' => !$roomMessage->is_like]);

        return response()->json(['message' => 'success']);
    }

    /**
     * @return string
     */
    private function defaultMessage()
    {
        return "Hi, Thank you for using MyPet!\nHow can we help you today?";
    }
}
