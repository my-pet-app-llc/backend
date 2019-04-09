<?php

namespace App\Http\Controllers\API;

use App\Friend;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FriendController extends Controller
{
    /**
     * @OA\Get(
     *     path="friends",
     *     tags={"Friends"},
     *     description="Get pet friends.",
     *     summary="All friends",
     *     operationId="friendsAll",
     *     @OA\Response(
     *         response="200",
     *         description="Friends of pet.",
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
        $pet = auth()->user()->owner->pet;

        return response()->json(FriendResource::collection($pet->friends));
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
     * Display the specified resource.
     *
     * @param  \App\Friend  $friend
     * @return Response
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Friend  $friend
     * @return Response
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Friend  $friend
     * @return Response
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Friend  $friend
     * @return Response
     */
    public function destroy(Friend $friend)
    {
        //
    }
}
