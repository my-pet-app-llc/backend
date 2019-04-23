<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Jenssegers\Agent\Agent;

class ApiResetPassword extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $agent = new Agent();
        if(!$agent->is('AndroidOs') && !$agent->is('IOs'))
            abort(403, 'Only for mobile device.');

        $user = User::query()->where('email', $request->input('email'))->first();
        $token = $request->input('token');

        if(!$user)
            abort(404, 'Not found user for restoring password.');

        $broker = Password::broker();
        if(!$broker->tokenExists($user, $token))
            abort(404, 'Not found token match.');

        $appUrl = env('MOB_APP_LINK') . env('MOB_APP_LINK_RESET_PASSWORD') . '?token=' . $token . '&email=' . $user->email;

        return redirect($appUrl);
    }
}
