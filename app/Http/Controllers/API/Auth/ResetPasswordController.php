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

    private function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
