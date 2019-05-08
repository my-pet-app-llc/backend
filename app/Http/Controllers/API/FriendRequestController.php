<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\Friendship\Friendship;
use App\Connect;
use App\FriendRequest;
use App\Http\Resources\FriendRequestsResource;
use App\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class FriendRequestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/friend-requests",
     *     tags={"Friend Requests"},
     *     description="Get all friend requests",
     *     summary="Get friend requests",
     *     operationId="friendRequestsGet",
     *     @OA\Response(
     *         response="200",
     *         description="First find matches for user. If user not have matches - find users who are within a radius of 20 miles",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Friiend Request ID"
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

        $request = $owner->friendRequests()->with('requested.pet')->get();

        return response()->json(FriendRequestsResource::collection($request));
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
     *     path="/friend-requests",
     *     tags={"Friend Requests"},
     *     description="Make friend request",
     *     summary="Make friend request",
     *     operationId="friendRequestSave",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/from-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="owner_id",
     *                     description="Owner id for friend request",
     *                     example="1"
     *                 ),
     *                 required={"match"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Friend request stored.",
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

        $friendship = new Friendship($owner, (int)$request->get('owner_id'));
        if($friendship->isStatus(Friendship::MATCH))
            $friendship->closeMatch();

        $friendship->makeFriendRequest();

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
     *     path="/friend-requests/{friend_reques_id}",
     *     tags={"Friend Requests"},
     *     description="Accept or decline friend request",
     *     summary="Accept or decline request",
     *     @OA\Parameter(
     *         name="friend_reques_id",
     *         description="Friend Request ID",
     *         required=true,
     *         in="path",
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
     *                     property="accept",
     *                     description="If 1 - accepte friend request, if 0 - decline",
     *                     example="1"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Friend request updated.",
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
     *                 example="No query result from model.|Request not found."
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
     * @param FriendRequest $friendRequest
     * @return Response
     * @throws \App\Exceptions\FriendshipException
     */
    public function update(Request $request, FriendRequest $friendRequest)
    {
        $owner = $request->user()->owner;
        $accept = (bool)$request->get('accept');

        $friendship = new Friendship($owner, (int)$friendRequest->requesting_owner_id);
        $friendship->updateFriendRequest($accept);

        return response()->json(['message' => 'success']);
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
