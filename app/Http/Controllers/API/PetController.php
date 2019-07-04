<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\Friendship\Friendship;
use App\Connect;
use App\Exceptions\FriendshipException;
use App\Http\Resources\FriendResource;
use App\Http\Resources\OwnerResource;
use App\Pet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
     *     path="/pets/{pet_id}",
     *     tags={"Other Profile"},
     *     description="Other pet and owner profile with friends",
     *     summary="Get other profile info",
     *     operationId="getOtherProfile",
     *     @OA\Parameter(
     *         name="pet_id",
     *         description="Pet ID whose profile you want to see",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Owner, pet and her friends data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Owner ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="first_name",
     *                 description="Owner first name",
     *                 example="John"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="last_name",
     *                 description="Owner last name",
     *                 example="Doe"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="gender",
     *                 description="Owner gender",
     *                 example="male"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="age",
     *                 description="Owner age",
     *                 example="11"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="birthday",
     *                 description="Owner birthday",
     *                 example="1970-01-01"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="occupation",
     *                 description="Owner occupation",
     *                 example="Developer"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="hobbies",
     *                 description="Owner hobbies",
     *                 example="Development"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="pets_owned",
     *                 description="Owner pets owned",
     *                 example="33 cows"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="profile_picture",
     *                 description="Owner profile picture URL",
     *                 example="http://mypets.com/storage/profile_picture/example.png"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="favorite_park",
     *                 description="Owner favorite pet park",
     *                 example="Park"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="pet",
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Pet ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="name",
     *                     description="Pet name",
     *                     example="Pet"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="gender",
     *                     description="Pet gender",
     *                     example="male"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="size",
     *                     description="Pet size",
     *                     example="giant"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="primary_breed",
     *                     description="Pet primary breed",
     *                     example="Dog"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="secondary_breed",
     *                     description="Pet secondary breed",
     *                     example="Cat"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="age",
     *                     description="Pet age",
     *                     example="11"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="profile_picture",
     *                     description="Pet profile picture URL",
     *                     example="http://mypets.com/storage/profile_picture/example.png"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="friendliness",
     *                     description="Pet friendliness",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="activity_level",
     *                     description="Pet activity level",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="noise_level",
     *                     description="Pet noise level",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="odebience_level",
     *                     description="Pet odebience level",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="fetchability",
     *                     description="Pet fetchability",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="swimability",
     *                     description="Pet swimability",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="city",
     *                     description="Pet city",
     *                     example="New York"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="state",
     *                     description="Pet state",
     *                     example="NY"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="like",
     *                     description="Pet likes",
     *                     example="Apples"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="dislike",
     *                     description="Pet dislikes",
     *                     example="Bananas"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="favorite_toys",
     *                     description="Pet favorite toys",
     *                     example="iPhone"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="fears",
     *                     description="Pet fears",
     *                     example="Water"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="favorite_places",
     *                     description="Pet favorite places",
     *                     example="Park"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="spayed",
     *                     description="Pet spayed. If 0 - not spayed, if 1 - spayed",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="birthday",
     *                     description="Pet birthday",
     *                     example="2000-01-24"
     *                 ),
     *                 @OA\Property(
     *                     type="array",
     *                     property="pictures",
     *                     description="Pet pictures",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             type="integer",
     *                             property="id",
     *                             description="Picture ID",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             type="string",
     *                             property="picture",
     *                             description="URL of picture",
     *                             example="http://mypets.com/storage/pictures/example.png"
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     type="array",
     *                     property="friends",
     *                     description="Pet friends only those who are friends with an authenticated user.",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             description="Pet ID",
     *                             example="2"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             description="Pet name",
     *                             example="Nona"
     *                         ),
     *                         @OA\Property(
     *                             property="gender",
     *                             type="string",
     *                             description="Pet gender",
     *                             example="male"
     *                         ),
     *                         @OA\Property(
     *                             property="size",
     *                             type="string",
     *                             description="Pet size",
     *                             example="giant"
     *                         ),
     *                         @OA\Property(
     *                             property="primary_breed",
     *                             type="string",
     *                             description="Pet primary breed",
     *                             example="Dog"
     *                         ),
     *                         @OA\Property(
     *                             property="secondary_breed",
     *                             type="string",
     *                             description="Pet secondary breed",
     *                             example="Pit"
     *                         ),
     *                         @OA\Property(
     *                             property="age",
     *                             type="integer",
     *                             description="Pet age",
     *                             example="11"
     *                         ),
     *                         @OA\Property(
     *                             property="profile_picture",
     *                             type="string",
     *                             description="Pet profile picture URL",
     *                             example="http://mypets.com/storage/profile_picture/example.png"
     *                         ),
     *                         @OA\Property(
     *                             property="friendliness",
     *                             type="integer",
     *                             description="Pet friendliness",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="activity_level",
     *                             type="integer",
     *                             description="Pet activity level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="noise_level",
     *                             type="integer",
     *                             description="Pet noise level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="odebience_level",
     *                             type="integer",
     *                             description="Pet odebience level",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="fetchability",
     *                             type="integer",
     *                             description="Pet fetchability",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="swimability",
     *                             type="integer",
     *                             description="Pet swimability",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="city",
     *                             type="string",
     *                             description="Pet city",
     *                             example="New York"
     *                         ),
     *                         @OA\Property(
     *                             property="state",
     *                             type="string",
     *                             description="Pet state",
     *                             example="NY"
     *                         ),
     *                         @OA\Property(
     *                             property="like",
     *                             type="string",
     *                             description="Pet likes",
     *                             example="Apples"
     *                         ),
     *                         @OA\Property(
     *                             property="dislike",
     *                             type="string",
     *                             description="Pet dislikes",
     *                             example="Bananas"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_toys",
     *                             type="string",
     *                             description="Pet favorite toys",
     *                             example="iPhone"
     *                         ),
     *                         @OA\Property(
     *                             property="fears",
     *                             type="string",
     *                             description="Pet fears",
     *                             example="Water"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_places",
     *                             type="string",
     *                             description="Pet favorite places",
     *                             example="Park"
     *                         ),
     *                         @OA\Property(
     *                             property="spayed",
     *                             type="string",
     *                             description="Pet spayed. If 0 - not spayed, if 1 - spayed",
     *                             example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="date",
     *                             description="Pet birthday",
     *                             example="2000-01-24"
     *                         ),
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="is_friends",
     *                 description="Are users friends"
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="to_friend_request",
     *                 description="Can send a friend request to the user"
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="to_send_message",
     *                 description="Can send a message to the user"
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
     * @param Pet $pet
     * @return Response
     * @throws FriendshipException
     */
    public function show(Pet $pet)
    {
        $ownerResource = (new OwnerResource($pet->owner))->toArray(request());

        $authOwner = auth()->user()->owner;
        $authFriends = $authOwner->pet->friends->pluck('friend_id')->toArray();
        $authFriends[] = $authOwner->pet->id;
        $ownerResource['pet'] = $ownerResource['pet']->toArray(request());
        $ownerResource['pet']['friends'] = FriendResource::collection($pet->friends->whereIn('friend_id', $authFriends))->toArray(request());

        $friendship = new Friendship($authOwner, $pet->owner);
        $friendshipStatus = $friendship->getStatus();

        $ownerResource['is_friends'] = ($friendshipStatus == Friendship::FRIENDS);

        $ownerResource['to_friend_request'] = (
            $friendshipStatus == Friendship::MATCH_CLOSED ||
            ($friendshipStatus == Friendship::MATCH &&
                $friendship->getMatch()->matches == Connect::MATCHES['all_matches'])
        );

        $accessStatuses = [Friendship::FRIENDS, Friendship::FRIEND_REQUEST, Friendship::MATCH_CLOSED, Friendship::MATCH];
        $ownerResource['to_send_message'] = (
            ($statusKey = array_search($friendshipStatus, $accessStatuses)) !== false &&
            ($accessStatuses[$statusKey] != Friendship::MATCH ||
                $friendship->getMatch()->matches == Connect::MATCHES['all_matches'])
        );

        return response()->json($ownerResource);
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
