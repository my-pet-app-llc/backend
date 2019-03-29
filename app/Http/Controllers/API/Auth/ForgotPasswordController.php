<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\NotOwnerException;
use App\Http\Requests\API\ForgotPasswordRequest;
use App\Notifications\API\ResetPassword;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct ()
    {
        $this->middleware('guest');
    }

    /**
     * Handle the incoming request.
     *
     * @param ForgotPasswordRequest $request
     * @return Response
     * @throws NotOwnerException
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $user = User::query()->where('email', $request->email)->first();

        if(!$user->isPetOwner())
            throw new NotOwnerException();

        if($user->isFacebookUser())
            return response()->json(['message' => 'User is registered through Facebook.'], 403);

        $broker = Password::broker();
        $token = $broker->createToken($user);

        $user->notify(new ResetPassword($token, $user->email));

        return response()->json(['message' => 'success']);
    }
}
