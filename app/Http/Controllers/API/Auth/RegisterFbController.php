<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\RegisterFbRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Owner;
use App\Pet;
use App\User;
use Illuminate\Http\Response;
use Storage;

class RegisterFbController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/fb/sign-up",
     *     tags={"Auth"},
     *     description="Facebook sign-up method",
     *     summary="Facebook sign-up",
     *     operationId="signUpFb",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="fb_token",
     *                     type="string",
     *                     description="Facebook user auth token"
     *                 ),
     *                 required={"fb_token"}
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
     *         description="Facebook error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Facebook error"
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
     * @param  RegisterFbRequest $request
     * @return Response
     */
    public function __invoke(RegisterFbRequest $request)
    {
        $fb_user = $request->get('fb_user');

        $fb_user_name = $fb_user->name;
        if($fb_user_name){
            $nameElements = explode(' ', $fb_user_name);
            if(count($nameElements) >= 2){
                $request->merge([
                    'first_name' => implode(' ', array_slice($nameElements, 0, count($nameElements) - 1)),
                    'last_name' => $nameElements[count($nameElements) - 1]
                ]);
            }else{
                $request->merge([
                    'first_name' => $nameElements[0]
                ]);
            }
        }

        $fb_user_avatar = $fb_user->avatar_original;
        if($fb_user_avatar){
            $fileExt = '.jpg';
            $fname = str_random(30) . $fileExt;
            $relPath = '/profile_picture/' . $fname;
            $file_content = file_get_contents($fb_user_avatar);
            Storage::put($relPath, $file_content);
            $request->merge([
                'profile_picture' => '/storage' . $relPath
            ]);
        }

        $user = User::query()->create($request->only(['email', 'facebook_id']));
        $request->merge(['user_id' => $user->id]);
        $owner = Owner::query()->create($request->all());
        Pet::query()->create(['owner_id' => $owner->id]);

        $token = $user->apiLogin();

        $responseData = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return response()->json($responseData);
    }
}
