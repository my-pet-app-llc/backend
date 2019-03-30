<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSignUpDone
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if($user && $user->isPetOwner() && $user->owner->signup_step != 0)
            return response()->json(['message' => 'Sign-Up steps not done.'], 401);

        return $next($request);
    }
}
