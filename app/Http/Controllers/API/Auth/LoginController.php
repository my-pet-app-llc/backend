<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Hash;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/sign-in",
     *     tags={"Auth"},
     *     description="Login method",
     *     summary="Login",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="User password"
     *                 ),
     *                 required={"email","password"}
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
     *                     type="integer",
     *                     property="id",
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="email",
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
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
     *         description="User not pet owner or credentials not match error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You are not pet owner.|Credentials don't match."
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
     * )
     */
    /**
     * Handle the incoming request.
     *
     * @param  LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email', '');
        $password = $request->get('password', '');
        if(($user = $this->attempt($email, $password))){
            $token = $user->apiLogin();
        }else{
            return response()->json(['message' => "Credentials don't match."], 401);
        }

        $response = [
            'token' => $token,
            'user' => (new UserResource($user))
        ];

        if($user->owner->signup_step > 0){
            $response['registration_process'] = true;
            $response['current_step'] = $user->owner->signup_step;
        }

        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Auth"},
     *     description="Logout method",
     *     summary="Logout",
     *     operationId="logout",
     *     @OA\Response(
     *         response="200",
     *         description="Success message.",
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
     *         description="Unathenticated user",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unathenticated."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();

        return response()->json(['message' => 'success']);
    }

    private function attempt($email, $password)
    {
        $user = User::query()->where('email', $email)->first();

        if(!$user)
            return null;

        if(Hash::check($password, $user->password))
            return $user;
        else
            return null;
    }
}
