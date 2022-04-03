<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *      version="2.0.0",
 *      title="L5 OpenApi",
 *      description="Swagger UI",
 * )
 *
 *  @OA\Post (
 *     path="/api/testing",
 *     @OA\Response(response="200", description="An example resource mean")
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
