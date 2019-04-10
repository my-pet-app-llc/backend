<?php

namespace App\Http\Controllers\API;

use App\FriendRequest;
use App\Http\Resources\FriendRequestsResource;
use App\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class FriendRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $owner = auth()->user()->owner;

        $request = $owner->friendRequests()->with('requested.pet')->get();

        return response()->json(FriendRequestsResource::collection($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $ownerToFriend = Owner::with(['pet'])->findOrFail($request->get('owner_id'));
        $owner = $request->user()->owner;

        if($owner->pet->hasFriend($ownerToFriend->pet->id))
            return response()->json(['message' => 'User already you friend.'], 403);

        if($ownerToFriend->id == $owner->id)
            return response()->json(['message' => 'Do you want to fuck yourself?'], 403);

        $requests = $owner->getDeclinedOrNotRespondedRequestsWith($ownerToFriend->id);
        if($requests->count())
            return response()->json(['message' => 'There is no confirm friend request with this user.'], 403);

        if(!$owner->existMatch($ownerToFriend))
            return response()->json(['message' => 'It is not possible to make a friend request to this user.']);

        FriendRequest::query()->create([
            'requesting_owner_id' => $owner->id,
            'responding_owner_id' => $ownerToFriend->id
        ]);

        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FriendRequest $friendRequest
     * @return Response
     */
    public function update(Request $request, FriendRequest $friendRequest)
    {
        $owner = $request->user()->owner;

        if($friendRequest->responding_owner_id != $owner->id)
            return response()->json(['message' => 'Request not found.'], 403);

        if($friendRequest->accept != null)
            return response()->json(['message' => 'The request has already been responded.'], 403);

        $accept = (bool)$request->get('accept');

        $friendRequest->update(compact('accept'));

        if($accept)
            $owner->pet->makeFriend($friendRequest->requested->pet->id);

        return response()->json(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
