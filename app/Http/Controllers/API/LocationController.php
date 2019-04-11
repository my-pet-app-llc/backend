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
     * @OA\Get(
     *     path="/location",
     *     tags={"Connect"},
     *     description="Update user location",
     *     summary="Update location",
     *     operationId="loctionUpdate",
     *     @OA\Parameter(
     *         name="lat",
     *         description="Latitude",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         description="Longitude",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Updated location successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
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
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="field",
     *                 @OA\Items(type="string", example="Invalid data")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
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
