<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UpdateResource;
use App\Update;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UpdateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/updates",
     *     tags={"Updates and Reminders"},
     *     description="Get all updates and events reminders. Social events reminder - 24h. Care events reminder - 72h",
     *     summary="Get updates and reminders",
     *     operationId="updatesGet",
     *     @OA\Response(
     *         response="200",
     *         description="Updates and Reminders",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="object",
     *                 property="events",
     *                 description="Start date of events as key for events",
     *                 @OA\Property(
     *                     type="array",
     *                     property="START_DATE_EVENTS",
     *                     description="Social events from now to add 24h, care events from now to add 72h",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Event ID",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="type",
     *                             description="Type of event",
     *                             example="Care"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="name",
     *                             description="Name of event",
     *                             example="Event name"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="from_date",
     *                             description="Start date of event",
     *                             example="2019-04-12"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="from_time",
     *                             description="Start time of event",
     *                             example="12:00"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="to_time",
     *                             description="End time of event",
     *                             example="13:00"
     *                         ),
     *                         @OA\Property(
     *                             type="array",
     *                             property="repeat",
     *                             description="Repeat numbers of week",
     *                             @OA\Items(type="integer")
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="where",
     *                             description="Where to be event",
     *                             example="Park"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="notes",
     *                             description="Notes of event",
     *                             example="Note"
     *                         ),
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="updates",
     *                 description="Updates list",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         type="integer",
     *                         property="id",
     *                         description="Update ID",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="image",
     *                         description="Image of update",
     *                         example="http://mypets.com/storage/updates/example.com"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="title",
     *                         description="Title of update",
     *                         example="Title"
     *                     ),
     *                     @OA\Property(
     *                         type="string",
     *                         property="description",
     *                         description="Description of update",
     *                         example="Description"
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
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pet = auth()->user()->pet;
        $events = $pet->reminder();
        $updates = Update::query()->latest('created_at')->get();

        $updates = UpdateResource::collection($updates);

        return response()->json(compact('events', 'updates'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/updates/{update_id}",
     *     tags={"Updates and Reminders"},
     *     description="Get update details",
     *     summary="Get update details",
     *     operationId="updateGet",
     *     @OA\Parameter(
     *         name="update_id",
     *         description="Update ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Update details",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Update ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="image",
     *                 description="Image of update",
     *                 example="http://mypets.com/storage/updates/example.com"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="title",
     *                 description="Title of update",
     *                 example="Title"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="description",
     *                 description="Description of update",
     *                 example="Description"
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
     * Display the specified resource.
     *
     * @param  Update $update
     * @return Response
     */
    public function show(Update $update)
    {
        return response()->json(new UpdateResource($update));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
