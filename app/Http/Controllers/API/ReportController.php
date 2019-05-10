<?php

namespace App\Http\Controllers\API;

use App\Events\NewTicketMessageEvent;
use App\Events\SupportTicketMessage;
use App\Exceptions\FriendshipException;
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
     * @OA\Post(
     *     path="/report",
     *     tags={"Report"},
     *     description="Report for user. Create ticket and create two chats",
     *     summary="Report for user",
     *     operationId="reportForUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="pet_id",
     *                     description="Reported pet ID",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="reason",
     *                     description="Report reason",
     *                     example="He is stuppid"
     *                 ),
     *                 required={"pet_id", "reason"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="New ticket data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="ticket_id",
     *                 description="New ticket ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="room_id",
     *                 description="New room id for ticket",
     *                 example="1"
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
     * Handle the incoming request.
     *
     * @param StoreReportRequest $request
     * @return Response
     * @throws FriendshipException
     */
    public function __invoke(StoreReportRequest $request)
    {
        $owner = auth()->user()->owner;
        $reportedOwner = Pet::with(['owner'])->find($request->get('pet_id'))->owner;
        $reportReason = $request->get('reason');

        if($owner->id == $reportedOwner->id)
            throw new FriendshipException('You cannot perform actions with yourself.');

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

        return response()->json([
            'ticket_id' => $ticket->id,
            'room_id' => $ticket->supportChats->where('owner_id', $owner->id)->first()->id
        ]);
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
