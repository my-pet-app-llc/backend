<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\Chat\Chat;
use App\Event;
use App\EventInvite;
use App\Http\Requests\API\EventIndexRequest;
use App\Http\Requests\API\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\FriendResource;
use App\Http\Resources\PreviewEventResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/events",
     *     tags={"Events"},
     *     description="Get events for buy date. If not send date from and to - get events for current month.",
     *     summary="Get events",
     *     operationId="eventsGet",
     *     @OA\Parameter(
     *         name="from_date",
     *         description="From date",
     *         in="query",
     *         @OA\Schema(
     *             type="date",
     *             format="Y-m-d"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="to_date",
     *         description="To date",
     *         in="query",
     *         @OA\Schema(
     *             type="date",
     *             format="Y-m-d"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Events",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="object",
     *                 property="date_of_selected_period",
     *                 @OA\Property(
     *                     type="object",
     *                     property="event_id_as_object_key",
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="type"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="name"
     *                     ),
     *                     @OA\Property(
     *                         type="date",
     *                         property="from_date"
     *                     ),
     *                     @OA\Property(
     *                         type="time",
     *                         property="from_time"
     *                     ),
     *                     @OA\Property(
     *                         type="time",
     *                         property="to_time"
     *                     ),
     *                     @OA\Property(
     *                         type="array",
     *                         property="repeat",
     *                         @OA\Items(type="integer")
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="where"
     *                     ),
     *                     @OA\Property(
     *                         type="boolean",
     *                         property="is_creator"
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
     * @param EventIndexRequest $request
     * @return Response
     */
    public function index(EventIndexRequest $request)
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
        $responseData = $pet->getEventsByDates($fromDate, $toDate);

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
     * @OA\Post(
     *     path="/events",
     *     tags={"Events"},
     *     description="Create new event.",
     *     summary="Create event",
     *     operationId="eventCreate",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Name of event",
     *                     example="New event"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="type",
     *                     description="Must be social or care",
     *                     example="social"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="from_date",
     *                     description="Date for event. Format Y-m-d. Date from now.",
     *                     example="2019-03-27"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="from_time",
     *                     description="Time start event. Format H:i",
     *                     example="12:30"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="to_time",
     *                     description="Time finish event. Format H:i. Must be more event start time.",
     *                     example="13:30"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="repeat[0]",
     *                     description="Number day of week",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="where",
     *                     description="Where to be event",
     *                     example="Park"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="notes",
     *                     description="Notes for event",
     *                     example="I'm stupid"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="invite[0]",
     *                     description="Id of the pet friends",
     *                     example="2"
     *                 ),
     *                 required={"name", "type", "from_date", "from_time", "to_time", "where"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Event data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Event ID",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type",
     *                 description="Type of event. Social or care",
     *                 example="social"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="name",
     *                 description="Event name",
     *                 example="First event"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_date",
     *                 description="Event start date",
     *                 example="2019-03-27"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_time",
     *                 description="Event start time",
     *                 example="12:30"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="to_time",
     *                 description="Event end time",
     *                 example="13:30"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="repeat",
     *                 @OA\Items(type="integer", description="Number day of week repeat event", example="2")
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="where",
     *                 description="Where event to be",
     *                 example="Park"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="notes",
     *                 description="Event notes",
     *                 example="I'm stupid"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="invited",
     *                 @OA\Items(
     *                     description="Invited pets",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="size",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="primary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="secondary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="friendliness",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="activity_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="noise_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="odebience_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="fetchability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="swimability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="city",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="state",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="like",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="dislike",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_toys",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="fears",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_places",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="spayed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                 )
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
     *         description="Not found exseption",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Some friends not found."
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
     * Store a newly created resource in storage.
     *
     * @param EventStoreRequest $request
     * @return Response
     */
    public function store(EventStoreRequest $request)
    {
        $request->merge([
            'type' => Event::TYPES[$request->get('type')],
            'repeat' => array_unique((array)$request->get('repeat', []))
        ]);

        $event = new Event($request->except(['invite']));
        $request->user()->pet->events()->save($event);

        $inviting = array_unique((array)$request->get('invite', []));
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
     * @OA\Get(
     *     path="/events/{event_id}",
     *     tags={"Events"},
     *     description="Get event data.",
     *     summary="Get event",
     *     operationId="eventGet",
     *     @OA\Parameter(
     *         name="event_id",
     *         description="Event ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Event data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Event ID",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type",
     *                 description="Type of event. Social or care",
     *                 example="social"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="name",
     *                 description="Event name",
     *                 example="First event"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_date",
     *                 description="Event start date",
     *                 example="2019-03-27"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_time",
     *                 description="Event start time",
     *                 example="12:30"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="to_time",
     *                 description="Event end time",
     *                 example="13:30"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="repeat",
     *                 @OA\Items(type="integer", description="Number day of week repeat event", example="2")
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="where",
     *                 description="Where event to be",
     *                 example="Park"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="notes",
     *                 description="Event notes",
     *                 example="I'm stupid"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="invited",
     *                 @OA\Items(
     *                     description="Invited pets",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="size",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="primary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="secondary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="friendliness",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="activity_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="noise_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="odebience_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="fetchability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="swimability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="city",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="state",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="like",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="dislike",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_toys",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="fears",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_places",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="spayed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                 )
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
     *                 example="Event not found."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Display the specified resource.
     *
     * @param  Event  $event
     * @return Response
     */
    public function show(Event $event)
    {
        $pet = auth()->user()->owner->pet;

//        if(!$pet->events()->where('id', $event->id)->first() || !$pet->eventInvites()->where('id', $event->id)->first())
//            throw new NotFoundHttpException('Event not found.');

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
     * @OA\Put(
     *     path="/events/{event_id}",
     *     tags={"Events"},
     *     description="Update exist event.",
     *     summary="Update event",
     *     operationId="eventUpdate",
     *     @OA\Parameter(
     *         name="event_id",
     *         description="Event ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Name of event",
     *                     example="New event"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="type",
     *                     description="Must be social or care",
     *                     example="social"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="from_date",
     *                     description="Date for event. Format Y-m-d. Date from now.",
     *                     example="2019-03-27"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="from_time",
     *                     description="Time start event. Format H:i",
     *                     example="12:30"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="to_time",
     *                     description="Time finish event. Format H:i. Must be more event start time.",
     *                     example="13:30"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="repeat[0]",
     *                     description="Number day of week",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="where",
     *                     description="Where to be event",
     *                     example="Park"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="notes",
     *                     description="Notes for event",
     *                     example="I'm stupid"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="invite[0]",
     *                     description="ID of the pet friends for new invite",
     *                     example="2"
     *                 ),
     *                 required={"name", "type", "from_date", "from_time", "to_time", "where"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Event data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Event ID",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="type",
     *                 description="Type of event. Social or care",
     *                 example="social"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="name",
     *                 description="Event name",
     *                 example="First event"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_date",
     *                 description="Event start date",
     *                 example="2019-03-27"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="from_time",
     *                 description="Event start time",
     *                 example="12:30"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="to_time",
     *                 description="Event end time",
     *                 example="13:30"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="repeat",
     *                 @OA\Items(type="integer", description="Number day of week repeat event", example="2")
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="where",
     *                 description="Where event to be",
     *                 example="Park"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="notes",
     *                 description="Event notes",
     *                 example="I'm stupid"
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="invited",
     *                 @OA\Items(
     *                     description="Invited pets",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="size",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="primary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="secondary_breed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="friendliness",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="activity_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="noise_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="odebience_level",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="fetchability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="swimability",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="city",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="state",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="like",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="dislike",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_toys",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="fears",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_places",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="spayed",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                 )
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
     *         description="Not found exseption",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Some friends not found.|No query result from model."
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
            'repeat' => array_unique((array)$request->get('repeat', []))
        ]);

        $event->update($request->except('invite'));

        $invitingIds = array_unique((array)$request->get('invite', []));
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
     * @OA\Delete(
     *     path="/events/{event_id}",
     *     tags={"Events"},
     *     description="Delete exist event.",
     *     summary="Delete event",
     *     operationId="eventDelete",
     *     @OA\Parameter(
     *         name="event_id",
     *         description="Event ID",
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
     *         description="Not found exseption",
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
     * @OA\Get(
     *     path="/events/friends/{event_id}",
     *     tags={"Events"},
     *     description="Get friends of pet who were not invited or declined the invited.",
     *     summary="Friends of pet for event.",
     *     operationId="eventsFriends",
     *     @OA\Parameter(
     *         name="event_id",
     *         description="Event ID if event already exist",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Friends of pet who were not invited or declined the invited.",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="primary_breed",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="secondary_breed",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="friendliness",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="activity_level",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="noise_level",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="odebience_level",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="fetchability",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="swimability",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="city",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="state",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="like",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="dislike",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="favorite_toys",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="fears",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="favorite_places",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="spayed",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="birthday",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="pictures",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="picture",
     *                             type="string"
     *                         )
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
     *         response="404",
     *         description="Not found exseption",
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
