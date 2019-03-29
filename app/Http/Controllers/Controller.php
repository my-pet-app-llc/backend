<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0.0",
     *         title="MyPets",
     *         description="bark",
     *     ),
     *     @OA\Server(
     *         description="OpenApi host",
     *         url="/api"
     *     ),
     *     @OA\PathItem(
     *         path="api/documentation"
     *     )
     * )
     */
    /**
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      type="http",
     *      scheme="bearer"
     * )
     **/
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
