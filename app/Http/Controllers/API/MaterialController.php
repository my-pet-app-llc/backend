<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MaterialResource;
use App\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class MaterialController extends Controller
{
    /**
     * @OA\Get(
     *     path="/materials",
     *     tags={"Materials"},
     *     description="Get list of partner materials",
     *     summary="Get materials",
     *     operationId="materialsGet",
     *     @OA\Response(
     *         response="200",
     *         description="List of partner materials",
     *         @OA\JsonContent(
     *             @OA\Items(
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                     description="Material ID",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="image",
     *                     description="URL of the materialimage",
     *                     example="http://mypets.com/storage/materials/example.com"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="title",
     *                     description="Title of material",
     *                     example="Title"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="short_text",
     *                     description="Short text for preview material",
     *                     example="That's short text"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="full_text",
     *                     description="Full text of material",
     *                     example="That's long long full text...."
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="address",
     *                     description="Address of partner",
     *                     example="Avenida Corrientes 1400, Автономный город Буэнос-Айрес, Аргентина"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="lat",
     *                     description="Latitude for address of partner",
     *                     example="12.23324234"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="lng",
     *                     description="Longitude for address of partner",
     *                     example="-34.123213123"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="phone_number",
     *                     description="Phone number of partner",
     *                     example="(098) 764-1920"
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="website",
     *                     description="Website of partner",
     *                     example="https://google.com"
     *                 ),
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $responseData = MaterialResource::collection(Material::query()->latest()->get());

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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/materials/{material_id}",
     *     tags={"Materials"},
     *     description="Get material details",
     *     summary="Material details",
     *     operationId="materialsDetails",
     *     @OA\Parameter(
     *         name="material_id",
     *         description="Material ID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of partner materials",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="integer",
     *                 property="id",
     *                 description="Material ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="image",
     *                 description="URL of the materialimage",
     *                 example="http://mypets.com/storage/materials/example.com"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="title",
     *                 description="Title of material",
     *                 example="Title"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="short_text",
     *                 description="Short text for preview material",
     *                 example="That's short text"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="full_text",
     *                 description="Full text of material",
     *                 example="That's long long full text...."
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="address",
     *                 description="Address of partner",
     *                 example="Avenida Corrientes 1400, Автономный город Буэнос-Айрес, Аргентина"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="lat",
     *                 description="Latitude for address of partner",
     *                 example="12.23324234"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="lng",
     *                 description="Longitude for address of partner",
     *                 example="-34.123213123"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="phone_number",
     *                 description="Phone number of partner",
     *                 example="(098) 764-1920"
     *             ),
     *             @OA\Property(
     *                 type="string",
     *                 property="website",
     *                 description="Website of partner",
     *                 example="https://google.com"
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
     *     security={{"bearerAuth":{}}}
     * )
     */
    /**
     * Display the specified resource.
     *
     * @param  Material $material
     * @return Response
     */
    public function show(Material $material)
    {
        $responseData = new MaterialResource($material);

        return response()->json($responseData);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
