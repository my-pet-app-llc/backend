<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\Chat\Chat;
use App\Event;
use App\EventInvite;
use App\Http\Requests\API\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\FriendResource;
use App\Http\Resources\PreviewEventResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="events",
     *     tags={"Events"},
     *     description="Get events for buy date. If not send date from and to - get events for current month.",
     *     summary="Get events",
     *     operationId="eventsGet",
     *     @OA\Parameter(
     *         name="from_date",
     *         description="From date",
     *         in="path",
     *         @OA\Schema(
     *             type="date",
     *             format="Y-m-d"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="to_date",
     *         description="To date",
     *         in="path",
     *         @OA\Schema(
     *             type="date",
     *             format="Y-m-d"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Events",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                 type="integer",
     *                 property="id"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="name"
     *             ),
     *             @OA\Property(
     *                 type="date",
     *                 property="from_date"
     *             ),
     *             @OA\Property(
     *                 type="time",
     *                 property="from_time"
     *             ),
     *             @OA\Property(
     *                 type="time",
     *                 property="to_time"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="repeat",
     *                 @OA\Items(type="integer")
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="where"
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="is_creator"
     *             )
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        if($fromDate && $toDate){
            $fromDateCarbon = Carbon::parse($fromDate);
            $toDateCarbon = Carbon::parse($toDate);

            if(!$fromDateCarbon->isValid())
                return response()->json(['from_date' => ['Invalid date.']], 422);

            if(!$toDateCarbon->isValid())
                return response()->json(['to_date' => ['Invalid date.']], 422);

            if(strtotime($fromDate) > strtotime($toDate))
                return response()->json(['from_date' => ['From date must be before to date.']], 422);
        }else{
            $fromDate = Carbon::now()->startOfMonth();
            $dayOfWeekFromDate = $fromDate->dayOfWeek;
            if($dayOfWeekFromDate != 7){
                $fromDate->subDays($dayOfWeekFromDate);
            }
            $fromDate = $fromDate->format('Y-m-d');

            $toDate = Carbon::now()->endOfMonth();
            $dayOfWeekToDate = $toDate->dayOfWeek;
            if($dayOfWeekToDate != 6){
                $toDate->addDays(6 - $dayOfWeekToDate);
            }
            $toDate = $toDate->format('Y-m-d');
        }

        $pet = auth()->user()->pet;

        $petEvents = $pet->events()
            ->whereDate('from_date', '>=', $fromDate)
            ->whereDate('from_date', '<=', $toDate)
            ->get();
        $invitedEvents = $pet->eventInvites()
            ->with('event')
            ->whereHas('event', function ($query) use ($fromDate, $toDate) {
                $query->whereDate('from_date', '>=', $fromDate)->whereDate('from_date', '<=', $toDate);
            })
            ->where('accepted', true)
            ->get()
            ->pluck('event');

        $allEvents = $petEvents->merge($invitedEvents)->sortBy('from_date')->values();
        $responseData = PreviewEventResource::collection($allEvents);

        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventStoreRequest $request
     * @return Response
     */
    public function store(EventStoreRequest $request)
    {
        $request->merge([
            'type' => Event::TYPES[$request->get('type')],
            'repeat' => array_unique($request->get('repeat', []))
        ]);

        $event = new Event($request->except(['invite']));
        $request->user()->pet->events()->save($event);

        $inviting = array_unique($request->get('invite', []));
        if($inviting){
            $invitesModels = [];
            foreach ($inviting as $item) {
                $invitesModels[] = new EventInvite(['pet_id' => $item]);
            }
            $event->eventInvites()->saveMany($invitesModels);

            Chat::bulkFindOrCreate($inviting)->send('event', function ($room) use ($event) {

                $invite = $event->eventInvites->where('pet_id', $room->getRecipient()->id)->first();
                return ['event_id' => $event->id, 'event_invite_id' => $invite->id];

            }, false);
        }

        return response()->json(new EventResource($event));
    }

    /**
     * Display the specified resource.
     *
     * @param  Event  $event
     * @return Response
     */
    public function show(Event $event)
    {
        return response()->json(new EventResource($event));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Event $event
     * @return Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EventStoreRequest $request
     * @param Event $event
     * @return Response
     */
    public function update(EventStoreRequest $request, Event $event)
    {
        $request->user()->pet->events()->findOrFail($event->id);

        $request->merge([
            'type' => Event::TYPES[$request->get('type')],
            'repeat' => array_unique($request->get('repeat', []))
        ]);

        $event->update($request->except('invite'));

        $invitingIds = array_unique($request->get('invite', []));
        if($invitingIds){
            $invitedIds = [];
            foreach ($invitingIds as $invitingId) {
                $invite = EventInvite::query()->create([
                    'event_id' => $event->id,
                    'pet_id' => $invitingId
                ]);
                $invitedIds[$invitingId] = $invite->id;
            }

            Chat::bulkFindOrCreate($invitingIds)->send('event', function ($room) use ($invitedIds, $event) {

                $invite = $invitedIds[$room->getRecipient()->id];
                return ['event_id' => $event->id, 'event_invite_id' => $invite];

            }, false);
        }

        return response()->json(new EventResource($event));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Event $event
     * @return Response
     * @throws \Exception
     */
    public function destroy(Event $event)
    {
        auth()->user()->pet->events()->findOrFail($event->id);

        $event->chatEventMessages()->update(['deleted_at' => Carbon::now()]);
        $event->delete();

        return response()->json(['message' => 'success']);
    }

    /**
     * @param Event|null $event
     * @return JsonResponse
     */
    public function friends(Event $event = null)
    {
        $pet = auth()->user()->pet()->with('friends.friend')->first();

        if(!$event){
            $friends = $pet->friends;
        }else{
            $invitedPets = $event->eventInvites()
                ->whereNull('accepted')
                ->orWhere('accepted', true)
                ->pluck('pet_id');
            $friends = $pet->friends->whereNotIn('friend_id', $invitedPets);
        }

        return response()->json(FriendResource::collection($friends));
    }
}
