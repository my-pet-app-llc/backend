<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\LoginFbRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;

class LoginFbController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/fb/sign-in",
     *     tags={"Auth"},
     *     description="Facebook Login method",
     *     summary="Facebook Login",
     *     operationId="loginFb",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="fb_token",
     *                     type="string",
     *                     description="Facebook User Auth Token"
     *                 ),
     *                 required={"fb_token"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Access token, pet owner and pet info and if not done registration - current step of registration.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="token"
     *             ),
     *             @OA\Property(
     *                 type="boolean",
     *                 property="registration_process"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="current_step"
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
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="User not pet owner or Facebook error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You are not pet owner.|Facebook error"
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
     *     )
     * )
     */
    /**
     * Handle the incoming request.
     *
     * @param  LoginFbRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginFbRequest $request)
    {
        $user = User::query()->where('facebook_id', $request->get('facebook_id'))->first();
        $token = $user->apiLogin();

        $responseData = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        if(($step = $user->owner->signup_step) > 0){
            $responseData['registration_process'] = true;
            $responseData['current_step'] = $step;
        }

        return response()->json($responseData);
    }
}
