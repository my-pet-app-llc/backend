<?php

namespace App\Http\Controllers\APi;

use App\Connect;
use App\Events\MatchEvent;
use App\Http\Resources\OwnerResource;
use App\Owner;
use App\Pet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $owner = auth()->user()->owner;
        $inRequests = $owner->inRequest()->where('creator', 0);

        if($inRequests->count()){
            $responseData = (new OwnerResource(Owner::with('pet.pictures')->find($inRequests->first()->owner_id)))->toArray(request());
            $match = true;
        }else{
            $matchOwner = $owner->findToConnect();
            $responseData = $matchOwner ? (new OwnerResource($matchOwner))->toArray(request()) : [];
            $match = false;
        }

        if($responseData)
            $responseData['match'] = $match;

        return response()->json($responseData);
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
        $matchedOwner = Owner::query()->findOrFail($request->get('owner_id'));
        $pet = $request->user()->owner;

        if($pet->existMatch($matchedOwner))
            return response()->json(['This match already been taken.'], 403);

        $matches = $request->get('match') ? Connect::MATCHES['request_match'] : Connect::MATCHES['blacklist'];

        $connect = Connect::query()->create([
            'requesting_owner_id' => $pet->id,
            'responding_owner_id' => $matchedOwner->id,
            'matches' => $matches
        ]);

        if($matches)
            broadcast(new MatchEvent($matchedOwner->user, [
                'connect_id' => $connect->id
            ]));

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
     * @param  Connect $connect
     * @return Response
     */
    public function update(Request $request, Connect $connect)
    {
        $owner = $request->user()->owner;

        if($connect->responding_owner_id != $owner->id)
            return response()->json(['message' => 'Not found match'], 404);

        $matches = $request->get('match') ? Connect::MATCHES['all_matches'] : Connect::MATCHES['blacklist'];

        $connect->update(['matches' => $matches]);

        $not_seen_matches = (bool)$owner->notRespondedMatches->count();

        return response()->json(['message' => 'success', 'not_seen_matches' => $not_seen_matches]);
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
