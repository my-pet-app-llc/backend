<?php

namespace App\Http\Middleware;

use Closure;
use Socialite;
use GuzzleHttp\Psr7;

class FbUser
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
        try{
            $user = Socialite::driver('facebook')->userFromToken($request->fb_token);
        }catch(\Exception $e){
            $message = $this->parseError($e->getMessage());
            return response()->json(['message' => $message], 401);
        }

        $request->merge(['fb_user' => $user]);

        return $next($request);
    }

    private function parseError($message)
    {
        $returnMessage = 'Facebook connection failed.';
        $exceptionParts = explode('response:', $message);
        if(count($exceptionParts) > 1){
            $mainParts = explode('"message":', $exceptionParts[1]);
            if(count($mainParts) > 1){
                $mainPart = explode('"', $mainParts[1]);
                if(count($mainPart) > 1){
                    $returnMessage = $mainPart[1];
                }
            }
        }

        return $returnMessage;
    }
}
