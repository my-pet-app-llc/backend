<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AcceptInviteEventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AcceptInviteEventController extends Controller
{
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
