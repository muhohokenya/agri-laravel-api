<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="AgriStroom OpenApi Documentation",
 *      description="AgriStroom Swagger OpenApi Documentation",
 *      @OA\Contact(
 *          email="muhohoweb@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )

 *
 * @OA\Tag(
 *     name="AgriStroom",
 *     description="API Endpoints of AgriStroom"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests,DispatchesJobs, ValidatesRequests;
}
