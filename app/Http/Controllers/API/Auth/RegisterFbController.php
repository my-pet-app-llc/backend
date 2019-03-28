<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\RegisterFbRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Owner;
use App\Pet;
use App\User;
use Storage;

class RegisterFbController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  RegisterFbRequest $request
     * @return \Illuminate\Http\Response
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

        $personalAccess = env('APP_PERSONAL_ACCESS_CLIENT');
        $token = $user->createToken($personalAccess)->accessToken;

        $responseData = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return response()->json($responseData);
    }
}
