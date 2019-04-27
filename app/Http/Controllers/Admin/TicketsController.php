<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewTicketMessageEvent;
use App\Events\SupportTicketMessage;
use App\Http\Resources\SupportChatMessageResource;
use App\SupportChatMessage;
use App\SupportChatRoom;
use App\SupportChatTextMessage;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Yajra\DataTables\Facades\DataTables;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index');
    }

    public function data()
    {
        $tickets = [
            [
                'id' => 1,
                'username' => 'Peter Dark',
                'email'    => 'peter@gmail.com',
                'date'     => '02/03/2019',
                'time'     => '15:40:25',
                'ticket'   => 'Ticket First',
                'status'   => 'New'
            ],
            [
                'id' => 6,
                'username' => 'Peter Dark',
                'email'    => 'peter@gmail.com',
                'date'     => '02/03/2019',
                'time'     => '15:40:25',
                'ticket'   => 'Ticket First',
                'status'   => 'New'
            ]
        ];

        $tickets = collect($tickets);

        $datatables = DataTables::collection($tickets);
        

        return $datatables->make(true);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('supportChats.owner.user');
        $creator_room = $ticket->supportChats->where('owner_id', $ticket->owner_id)->first();
        $reported_room = $ticket->supportChats->where('owner_id', $ticket->reported_owner_id)->first();

        if($ticket->status == Ticket::STATUSES['new']){
            $ticket->update(['status' => Ticket::STATUSES['in_progress']]);
        }

        return response([
            'is_report' => (bool)$ticket->reported_owner_id,
            'is_resolved' => ($ticket->status == Ticket::STATUSES['resolved']),
            'status' => $ticket->status,
            'rooms' => [
                'creator_room' => [
                    'id' => $creator_room->id,
                    'email' => $creator_room->owner->user->email,
                    'full_name' => $creator_room->owner->fullName
                ],
                'reported_room' => $reported_room ? [
                        'id' => $reported_room->id,
                        'email' => $reported_room->owner->user->email,
                        'full_name' => $reported_room->owner->fullName
                    ] : null
            ]
        ]);
    }

    public function messages(Request $request, SupportChatRoom $room)
    {
        $messages = $room->supportChatMessages()
            ->with(['sender', 'messagable'])
            ->latest('id')
            ->when($request->get('last_message'), function ($query) use ($request) {
                return $query->where('id', '<', $request->get('last_message'));
            })
            ->take($request->get('paginate', 25))
            ->get();

        return response(SupportChatMessageResource::collection($messages));
    }

    public function sendMessage(Request $request, SupportChatRoom $room)
    {
        $this->validate($request, ['message' => 'required|string|min:1']);

        if($room->ticket->status == Ticket::STATUSES['resolved'])
            return response()->json(['message' => 'Ticket already resolved']);

        $messageTypeModel = SupportChatTextMessage::query()->create([
            'text' => $request->get('message')
        ]);

        $messageModel = $messageTypeModel->message()->save(new SupportChatMessage([
            'support_chat_room_id' => $room->id,
            'type' => SupportChatMessage::TYPES['text'],
            'sender_id' => 0
        ]));

        $messageResource = new SupportChatMessageResource($messageModel);

        broadcast(new SupportTicketMessage($room, $messageModel))->toOthers();

        broadcast(new NewTicketMessageEvent($room->owner->user, [
            'room_id' => $room->id,
            'message' => $messageResource
        ]));

        return response()->json($messageResource);
    }

    public function changeStatus(Request $request, Ticket $ticket)
    {
        $currentStatus = $ticket->status;
        $newStatus = $request->get('status');
        $resolvedStatus = Ticket::STATUSES['resolved'];

        if($currentStatus == $resolvedStatus)
            return response()->json(['message' => 'Ticket already resolved.'], 403);

        if($newStatus != $resolvedStatus)
            return response()->json(['message' => 'Status wrong.'], 403);

        $ticket->update([
            'status' => $newStatus
        ]);

        return response()->json(['message' => 'success']);
    }
}
