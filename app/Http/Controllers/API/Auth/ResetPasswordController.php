<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/password/reset",
     *     tags={"Auth"},
     *     description="Recovery password",
     *     summary="Recovery password",
     *     operationId="resetPassword",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Reset password token"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 required={"token","email","password","password_confirmation"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Access token, pet owner and pet info.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="token"
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
     *         description="User access error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="You are not pet owner."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Token parse error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Invalid user.|Invalid password.|Invalid token"
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
     * @param ResetPasswordRequest $request
     * @return Response
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $credentials = $request->all();

        $broker = Password::broker();
        $response = $broker->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if($response != Password::PASSWORD_RESET){
            if($response == Password::INVALID_USER){
                $message = 'Invalid user.';
            }elseif($response == Password::INVALID_PASSWORD){
                $message = 'Invalid password.';
            }else{
                $message = 'Invalid token';
            }

            return response()->json(['message' => $message], 403);
        }

        $user = User::query()->where('email', $credentials['email'])->first();
        $token = $user->apiLogin();

        $responseData = [
            'token' => $token,
            'user' => (new UserResource($user))
        ];

        return response()->json($responseData);
    }

    /**
     * @param $user
     * @param $password
     */
    private function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
