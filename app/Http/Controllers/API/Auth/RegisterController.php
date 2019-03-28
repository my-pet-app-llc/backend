<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Owner;
use App\Pet;
use App\User;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user = User::query()->create($request->all());
        $owner = Owner::query()->create(['user_id' => $user->id]);
        Pet::query()->create(['owner_id' => $owner->id]);

        $personalAccess = env('APP_PERSONAL_ACCESS_CLIENT');
        $accessToken = $user->createToken($personalAccess)->accessToken;

        $responseData = [
            'token' => $accessToken,
            'user' => new UserResource($user)
        ];

        return response()->json($responseData);
    }
}
