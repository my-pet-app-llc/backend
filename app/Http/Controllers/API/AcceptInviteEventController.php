<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AcceptInviteEventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AcceptInviteEventController extends Controller
{
    /**
     * @OA\Post(
     *     path="invited-events/accept",
     *     tags={"Chat"},
     *     description="Accept or decline invitation to event.",
     *     summary="Accept or decline invite",
     *     operationId="inviteAccept",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="accept",
     *                     description="Must be 1 or 0. 1 - Accept, 0 - Decline",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="integer",
     *                     property="invite_id",
     *                     description="Pet invite ID",
     *                     example="2"
     *                 ),
     *                 required={"accept", "invite_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             ),
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
     *     @OA\Response(
     *         response="403",
     *         description="Exist feedback from invite error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Invite already accepted.|Invite already declined."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found exseption",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="No query result from model."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Handle the incoming request.
     *
     * @param AcceptInviteEventRequest $request
     * @return Response
     */
    public function __invoke(AcceptInviteEventRequest $request)
    {
        $pet = $request->user()->pet;
        $accept = $request->get('accept');
        $inviteId = $request->get('invite_id');

        $invite = $pet->eventInvites()->findOrFail($inviteId);
        if($invite->accepted !== null){
            $action = $invite->accepted ? 'accepted' : 'declined';
            abort(403, "Invite already {$action}.");
        }

        $invite->update(['accepted' => $accept]);

        return response()->json(['message' => 'success']);
    }
}
