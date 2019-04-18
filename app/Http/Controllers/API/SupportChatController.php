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
     * @return JsonResponse
     */
    public function getDefaultMessage()
    {
        return response()->json(['message' => $this->defaultMessage()]);
    }

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
     * @return JsonResponse
     */
    public function isRead()
    {
        $notReadRooms = auth()->user()->owner->supportChatRooms()->where('is_read', false)->count();
        return response()->json(['is_read' => !$notReadRooms]);
    }

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
