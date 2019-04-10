<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\LocationRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Response;
use DB;

class LocationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param LocationRequest $request
     * @return Response
     */
    public function __invoke(LocationRequest $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $owner = $request->user()->owner;

        $owner->update([
            'location_point' => DB::raw("ST_PointFromText('POINT({$lat} {$lng})', 4326)"),
            'location_updated_at' => Carbon::now()
        ]);

        return response()->json(['message' => 'success']);
    }
}
