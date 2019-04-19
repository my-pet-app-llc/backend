<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/index",
     *     tags={"Main"},
     *     description="Main API call",
     *     summary="Main API call",
     *     operationId="mainCall",
     *     @OA\Response(
     *         response="200",
     *         description="Main info",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="friend_requests_count",
     *                 description="Friend requests count",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="integer",
     *                 property="new_matches_count",
     *                 description="New matches count",
     *                 example="1"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Sign-Up steps not done."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke()
    {
        $owner = auth()->user()->owner;

        $friendRequestsCount = $owner->friendRequests()->count();
        $matchesCount = $owner->notRespondedMatches()->count();

        return response([
            'friend_requests_count' => $friendRequestsCount,
            'new_matches_count' => $matchesCount
        ]);
    }
}
