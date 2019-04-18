<?php

namespace App\Http\Controllers\API;

use App\Events\NewTicketMessageEvent;
use App\Events\SupportTicketMessage;
use App\Http\Requests\API\StoreReportRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupportChatMessageResource;
use App\Owner;
use App\Pet;
use App\SupportChatMessage;
use App\SupportChatRoom;
use App\SupportChatSystemMessage;
use App\Ticket;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreReportRequest $request
     * @return Response
     */
    public function __invoke(StoreReportRequest $request)
    {
        $owner = auth()->user()->owner;
        $reportedOwner = Pet::with(['owner'])->find($request->get('pet_id'))->owner;
        $reportReason = $request->get('reason');

        $ticket = $owner->tickets()->save(new Ticket([
            'reported_owner_id' => $reportedOwner->id,
            'report_reason' => $reportReason,
            'status' => Ticket::STATUSES['reported_user']
        ]));

        $ticket->supportChats()->saveMany([
            new SupportChatRoom([
                'owner_id' => $owner->id
            ]),
            new SupportChatRoom([
                'owner_id' => $reportedOwner->id
            ])
        ]);

        $this->sendHandler($ticket, $owner, $reportedOwner);

        $reportedOwnerStatus = Owner::STATUS['reported'];
        if($reportedOwner->status == Owner::STATUS['in_progres'] || $reportedOwner->status == Owner::STATUS['reporting'])
            $reportedOwnerStatus = Owner::STATUS['reporting'];

        if($reportedOwner->status != $reportedOwnerStatus)
            $reportedOwner->update(['status' => $reportedOwnerStatus]);

        return response()->json(['message' => 'success']);
    }

    private function sendHandler(Ticket $ticket, Owner $authOwner, Owner $reportOwner)
    {
        $authRoom = $ticket->supportChats->where('owner_id', $authOwner->id)->first();
        $reportRoom = $ticket->supportChats->where('owner_id', $reportOwner->id)->first();

        $authMessage = $this->getAuthMessage($authOwner->fullName, $reportOwner->fullName, $ticket->report_reason);
        $reportMessage = $this->getReportMessage($reportOwner->fullName, $ticket->report_reason);

        $authSystemMessage = SupportChatSystemMessage::query()->create([
            'text' => $authMessage
        ]);
        $reportSystemMessage = SupportChatSystemMessage::query()->create([
            'text' => $reportMessage
        ]);

        $authChatMessage = $authSystemMessage->message()->save(new SupportChatMessage([
            'support_chat_room_id' => $authRoom->id,
            'type' => SupportChatMessage::TYPES['system']
        ]));

        $reportChatMessage = $reportSystemMessage->message()->save(new SupportChatMessage([
            'support_chat_room_id' => $reportRoom->id,
            'type' => SupportChatMessage::TYPES['system']
        ]));

        broadcast(new NewTicketMessageEvent($authOwner->user, [
            'room_id' => $authRoom->id,
            'message' => new SupportChatMessageResource($authChatMessage)
        ]));
        broadcast(new NewTicketMessageEvent($reportOwner->user, [
            'room_id' => $reportRoom->id,
            'message' => new SupportChatMessageResource($reportChatMessage)
        ]));

        broadcast(new SupportTicketMessage($authRoom, $authChatMessage));
        broadcast(new SupportTicketMessage($reportRoom, $reportChatMessage));
    }

    private function getAuthMessage($authFullName, $reportFullName, $reason)
    {
        return "Hi {$authFullName}, You've reported {$reportFullName} for the following reason: {$reason}. Please contact MyPets support for more information";
    }

    private function getReportMessage($reportFullName, $reason)
    {
        return "Hi {$reportFullName}, You've been reported for the following reason: {$reason}. Please contact MyPet support for more information";
    }
}
