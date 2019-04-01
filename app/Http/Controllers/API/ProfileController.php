<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\StoreFile\File;
use App\Http\Resources\UserResource;
use App\Pet;
use App\Picture;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * @var Request|null
     */
    public $request;

    /**
     * @var User|null
     */
    public $user;

    const ACTIONS = [
        'get' => 'show',
        'put' => 'update'
    ];

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $this->request = $request;
        $this->user = $request->user();

        $method = strtolower($request->getMethod());
        $action = self::ACTIONS[$method];

        $response = $this->$action();

        return $response;
    }

    /**
     * @OA\Get(
     *     path="profile",
     *     tags={"Profile"},
     *     description="Get profile data.",
     *     summary="Profile data",
     *     operationId="profile",
     *     @OA\Response(
     *         response="200",
     *         description="Success message, pet owner and pet info.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="user",
     *                 @OA\Property(
     *                     type="string",
     *                     property="email",
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                     @OA\Property(
     *                         property="occupation",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="hobbies",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="pets_owned",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_park",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="pet",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="gender",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="size",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="primary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="secondary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="age",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="profile_picture",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="friendliness",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="activity_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="noise_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="odebience_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="fetchability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="swimability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="city",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="state",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="like",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="dislike",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_toys",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="fears",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_places",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="spayed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="date"
     *                         ),
     *                         @OA\Property(
     *                             property="pictures",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="picture",
     *                                     type="string"
     *                                 )
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="friends",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="name",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="gender",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="size",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="primary_breed",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="secondary_breed",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="age",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="profile_picture",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="friendliness",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="activity_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="noise_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="odebience_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="fetchability",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="swimability",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="city",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="state",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="like",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="dislike",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="favorite_toys",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="fears",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="favorite_places",
     *                                      type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="spayed",
     *                                      type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="birthday",
     *                                      type="date"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="pictures",
     *                                     type="array",
     *                                     @OA\Items(
     *                                         @OA\Property(
     *                                             property="id",
     *                                             type="integer"
     *                                         ),
     *                                         @OA\Property(
     *                                             property="picture",
     *                                             type="string"
     *                                         )
     *                                     )
     *                                 ),
     *                             )
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
     * @return JsonResponse
     */
    protected function show()
    {
        $userResource = new UserResource($this->user, true, true, true, true);

        return response()->json(['user' => $userResource]);
    }

    /**
     * @OA\Put(
     *     path="profile",
     *     tags={"Profile"},
     *     description="Update owner and pet profiles.",
     *     summary="Update profile",
     *     operationId="profileUpdate",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="owner[first_name]",
     *                     type="string",
     *                     description="Owner first name. Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$",
     *                     example="Bob"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[last_name]",
     *                     type="string",
     *                     description="Owner last name.  Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$",
     *                     example="Marley"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[gender]",
     *                     type="string",
     *                     description="Owner gender. Rules: required, min - 1, max - 20, RegExp - ^(male|female|[[:alpha:]]+)$",
     *                     example="male"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[birthday]",
     *                     type="date",
     *                     description="Owner date of birthday. Rules: required, format - 'Y-m-d', before - 'now - 1 day'",
     *                     example="2000-01-01"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[occupation]",
     *                     type="string",
     *                     description="Owner occupation. Rules: required, min - 1, max - 128",
     *                     example="Developer"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[hobbies]",
     *                     type="string",
     *                     description="Owner hobbies. Rules: required, min - 1, max - 128",
     *                     example="Bike"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[favorite_park]",
     *                     type="string",
     *                     description="Owner favorite dog park. Rules: required, min - 1, max - 128",
     *                     example="Tarasa Shevchenka"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[pets_owned]",
     *                     type="string",
     *                     description="Owner pets owned. Rules: required, min - 1, max - 128",
     *                     example="33 cows"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[profile_picture]",
     *                     type="string",
     *                     description="Owner profile picture. Rules: optional, correct base64 string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="pet[friendliness]",
     *                     type="integer",
     *                     description="Pet friendliness. Rules: required, min - 1, max - 5",
     *                     example="5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[activity_level]",
     *                     type="integer",
     *                     description="Pet activity level. Rules: required, min - 1, max - 5",
     *                     example="3"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[noise_level]",
     *                     type="integer",
     *                     description="Pet noise level. Rules: required, min - 1, max - 5",
     *                     example="4"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[odebience_level]",
     *                     type="integer",
     *                     description="Pet odebience level. Rules: required, min - 1, max - 5",
     *                     example="4"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[fetchability]",
     *                     type="integer",
     *                     description="Pet fetchability. Rules: required, min - 1, max - 5",
     *                     example="4"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[swimability]",
     *                     type="integer",
     *                     description="Pet swimability. Rules: required, min - 1, max - 5",
     *                     example="5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[name]",
     *                     type="string",
     *                     description="Pet name. Rules: required, min - 1, max - 12, RegExp - ^([[:alpha:]-]+\s?)+$",
     *                     example="Dolly"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[gender]",
     *                     type="string",
     *                     description="Pet gender. Rules: required, in - [male, female]",
     *                     example="male"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[spayed]",
     *                     type="integer",
     *                     description="Pet spayed? Rules: required, in - [0, 1]",
     *                     example="0"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[size]",
     *                     type="string",
     *                     description="Pet size. Rules: required, in - [small, medium, large, giant]",
     *                     example="giant"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[age]",
     *                     type="integer",
     *                     description="Pet age. Rules: required, min - 1, max - 99",
     *                     example="18"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[birthday]",
     *                     type="date",
     *                     description="Pet date of birthday. Rules: required, format - 'Y-m-d', before - 'now - 1 day'",
     *                     example="1996-06-28"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[city]",
     *                     type="string",
     *                     description="Pet hometown. Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$",
     *                     example="New-York"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[state]",
     *                     type="string",
     *                     description="Pet state. Rules: required, min - 2, max - 3, RegExp - ^[A-Z]{2,3}$",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="pet[like]",
     *                     type="string",
     *                     description="Pet likes. Rules: required, min - 1, max - 128",
     *                     example="Orange"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[dislike]",
     *                     type="string",
     *                     description="Pet dislikes. Rules: required, min - 1, max - 128",
     *                     example="McDonald's"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[favorite_toys]",
     *                     type="string",
     *                     description="Pet favorite toys. Rules: required, min - 1, max - 128",
     *                     example="Mr. Sandman"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[fears]",
     *                     type="string",
     *                     description="Pet fears. Rules: required, min - 1, max - 128",
     *                     example="Death"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[favorite_places]",
     *                     type="string",
     *                     description="Pet favorite places. Rules: required, min - 1, max - 128",
     *                     example="Land"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[profile_picture]",
     *                     type="string",
     *                     description="Pet profile picture. Rules: optional, correct base64 string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="pet[pictures][create][0]",
     *                     type="string",
     *                     description="Upload pet pictures. Rules: optional, correct base64 string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="pet[pictures][delete][0]",
     *                     type="integer",
     *                     description="Delete pet picture. Rules: optional, picture id, exists in pet pictures",
     *                     example=""
     *                 ),
     *                 required={"owner[first_name]","owner[last_name]","owner[gender]","owner[birthday]","owner[occupation]","owner[hobbies]","owner[favorite_park]","owner[pets_owned]",
                                "pet[friendliness]","pet[activity_level]","pet[noise_level]","pet[odebience_level]","pet[fetchability]","pet[swimability]","pet[name]","pet[gender]",
                                "pet[spayed]","pet[size]","pet[age]","pet[birthday]","pet[city]","pet[state]","pet[like]","pet[dislike]","pet[favorite_toys]","pet[fears]","pet[favorite_places]"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message, pet owner and pet info.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="user",
     *                 @OA\Property(
     *                     type="string",
     *                     property="email",
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                     @OA\Property(
     *                         property="occupation",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="hobbies",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="pets_owned",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_park",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="pet",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="gender",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="size",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="primary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="secondary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="age",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="profile_picture",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="friendliness",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="activity_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="noise_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="odebience_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="fetchability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="swimability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="city",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="state",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="like",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="dislike",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_toys",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="fears",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_places",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="spayed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="date"
     *                         ),
     *                         @OA\Property(
     *                             property="pictures",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="picture",
     *                                     type="string"
     *                                 )
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="friends",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="name",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="gender",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="size",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="primary_breed",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="secondary_breed",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="age",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="profile_picture",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="friendliness",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="activity_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="noise_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="odebience_level",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="fetchability",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="swimability",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="city",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="state",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="like",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="dislike",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="favorite_toys",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="fears",
     *                                     type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="favorite_places",
     *                                      type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="spayed",
     *                                      type="string"
     *                                 ),
     *                                 @OA\Property(
     *                                      property="birthday",
     *                                      type="date"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="pictures",
     *                                     type="array",
     *                                     @OA\Items(
     *                                         @OA\Property(
     *                                             property="id",
     *                                             type="integer"
     *                                         ),
     *                                         @OA\Property(
     *                                             property="picture",
     *                                             type="string"
     *                                         )
     *                                     )
     *                                 ),
     *                             )
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
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="errors",
     *                 @OA\Property(type="array", property="parameter", @OA\Items(type="string",description="message"))
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    protected function update()
    {
        $owner = $this->user->owner;
        $ownerData = $this->request->only('owner')['owner'];

        $pet = $owner->pet;
        $petData = $this->request->only('pet')['pet'];

        if(isset($ownerData['profile_picture']))
            $this->setProfilePicture($ownerData['profile_picture'], $ownerData);

        if(isset($petData['profile_picture']))
            $this->setProfilePicture($petData['profile_picture'], $petData);

        if(isset($petData['pictures']))
            $this->petPicturesHandler($pet, $petData['pictures']);

        $owner->update($ownerData);
        $pet->update($petData);

        $responseData = [
            'user' => (new UserResource($this->user, true, true, true, true)),
            'message' => 'success'
        ];

        return response()->json($responseData);
    }

    /**
     * @param string $base64
     * @param $modelData
     * @throws ValidationException
     */
    private function setProfilePicture(string $base64, &$modelData)
    {
        $file = $this->makeFile($base64, 'profile_picture');
        if(!$file)
            unset($modelData['profile_picture']);
        else
            $modelData['profile_picture'] = $file;
    }

    /**
     * @param Pet $pet
     * @param $pictures
     * @throws ValidationException
     */
    private function petPicturesHandler(Pet $pet, $pictures)
    {
        if(isset($pictures['create']))
            $this->savePetPictures($pet, $pictures['create']);

        if(isset($pictures['delete']))
            $this->destroyPetPictures($pet, $pictures['delete']);
    }

    /**
     * @param Pet $pet
     * @param $pictures
     * @throws ValidationException
     */
    private function savePetPictures(Pet $pet, $pictures)
    {
        $pictureModels = [];

        foreach ($pictures as $picture) {
            $file = $this->makeFile($picture, 'pictures');
            if($file)
                $pictureModels[] = new Picture(['picture' => $file]);
        }

        $pet->pictures()->saveMany($pictureModels);
    }

    /**
     * @param Pet $pet
     * @param $pictures
     */
    private function destroyPetPictures(Pet $pet, $pictures)
    {
        $pet->pictures()->whereIn('id', $pictures)->delete();
    }

    /**
     * @param string $base64
     * @param string $path
     * @return string|null
     * @throws ValidationException
     */
    private function makeFile(string $base64, string $path)
    {
        $file = new File($base64);
        $file->validation(['jpg', 'png']);
        return $file->store($path);
    }
}
