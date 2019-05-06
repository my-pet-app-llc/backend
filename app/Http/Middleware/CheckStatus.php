<?php

namespace App\Http\Middleware;

use App\Exceptions\NotOwnerException;
use App\Owner;
use Closure;
use Illuminate\Http\Request;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws NotOwnerException
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $owner = $user->owner;

        if($owner->status == Owner::STATUS['banned']){
            $user->token()->revoke();
            throw new NotOwnerException('You were banned.');
        }

        return $next($request);
    }
}
