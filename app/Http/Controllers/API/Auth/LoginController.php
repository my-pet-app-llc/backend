<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        $email = $request->get('email', '');
        $password = $request->get('password', '');
        if(($user = $this->attempt($email, $password))){
            $token = $user->createToken(env('APP_PERSONAL_ACCESS_CLIENT'))->accessToken;
        }else{
            return response()->json(['message' => "Credentials don't match."]);
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
