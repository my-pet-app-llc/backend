<?php

namespace App\Http\Controllers\APi;

use App\Components\Classes\Friendship\Friendship;
use App\Connect;
use App\Events\MatchEvent;
use App\Http\Resources\OwnerResource;
use App\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ConnectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/connect",
     *     tags={"Connect"},
     *     description="Get user for connect",
     *     summary="Get user for connect",
     *     operationId="connect",
     *     @OA\Response(
     *         response="200",
     *         description="First find matches for user. If user not have matches - find users who are within a radius of 20 miles",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="match",
     *                 description="If match exist - connect_id, else 0",
     *                 example=0
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="owner",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="birthday",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="occupation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="hobbies",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="pets_owned",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="favorite_park",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="pet",
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
     *                     @OA\Property(
     *                         property="pictures",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer"
     *                             ),
     *                             @OA\Property(
     *                                 property="picture",
     *                                 type="string"
     *                             )
     *                         )
     *                     )
     *                 )
     *            )
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
     *                 example="No users found nearby."
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
        $owner = auth()->user()->owner;
        $inRequests = $owner->notClose()->where('creator', 0)->sortByDesc('closed');
        $responseData = [];

        if($inRequests->count()){
            $responseData['owner'] = (new OwnerResource(Owner::with('pet.pictures')->find($inRequests->first()->owner_id)))->toArray(request());
            $match = $inRequests->first()->id;
        }else{
            $matchOwner = $owner->findToConnect();
            $responseData = $matchOwner ? ['owner' => (new OwnerResource($matchOwner))->toArray(request())] : [];
            $match = 0;
        }

        if($responseData)
            $responseData['match'] = $match;
        else
            return response()->json(['message' => 'No users found nearby.'], 404);

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
     *     path="/connect",
     *     tags={"Connect"},
     *     description="Match accept or decline. Match dont exist",
     *     summary="Match accept or decline",
     *     operationId="connectSave",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/from-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="owner_id",
     *                     description="Owner ID for connect",
     *                     example="2"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="match",
     *                     description="1 - match accept, 0 - match decline",
     *                     example="1"
     *                 ),
     *                 required={"owner_id", "match"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message. Match decline or Accept. If match accept - broadcast fot connected owner.",
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
     *         response="403",
     *         description="Forbidden error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Message"
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws \App\Exceptions\FriendshipException
     */
    public function store(Request $request)
    {
        $owner = $request->user()->owner;

        $matches = $request->get('match') ? Connect::MATCHES['request_match'] : Connect::MATCHES['blacklist'];

        $friendship = new Friendship($owner, (int)$request->get('owner_id'));
        $friendship->makeMatch($matches);

        if($matches)
            broadcast(new MatchEvent($friendship->getFriendOwner()->user, [
                'connect_id' => $friendship->getMatch()->id
            ]));

        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
     * @OA\Put(
     *     path="/connect/{connect_id}",
     *     tags={"Connect"},
     *     description="Match accept or decline. For exist match",
     *     summary="Match accept or decline",
     *     operationId="connectUpdate",
     *     @OA\Parameter(
     *         name="connect_id",
     *         description="Connect ID",
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
     *                     type="integer",
     *                     property="match",
     *                     description="1 - match accept, 0 - match decline",
     *                     example="1"
     *                 ),
     *                 required={"match"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message. Match decline or Accept.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="not_seen_matches",
     *                 example=false,
     *                 description="User has not seen matches"
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
     *                 example="Message"
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
     *                 example="No query result from model.|Not found match"
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Connect $connect
     * @return Response
     * @throws \App\Exceptions\FriendshipException
     */
    public function update(Request $request, Connect $connect)
    {
        $owner = $request->user()->owner;

        $matches = $request->get('match') ? Connect::MATCHES['all_matches'] : Connect::MATCHES['blacklist'];

        $friendship = new Friendship($owner, (int)$connect->requesting_owner_id);
        $friendship->confirmMatch($matches);

        $not_seen_matches = (bool)$owner->notRespondedMatches->count();

        return response()->json(['message' => 'success', 'not_seen_matches' => $not_seen_matches]);
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
