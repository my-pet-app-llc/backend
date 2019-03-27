<?php

namespace App\Http\Middleware;

use Closure;

class SignUpStepMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if($user && $user->owner && $user->owner->signup_step == 0)
            return response()->json(['message' => 'Registration is  done.'], 401);

        return $next($request);
    }
}
