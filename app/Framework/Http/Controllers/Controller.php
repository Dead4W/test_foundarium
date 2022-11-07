<?php

namespace App\Framework\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Swagger(
 *   schemes={"http"},
 *   host="api.test.com",
 *   basePath="/",
 *   @OA\Info(title="API", version="1.00")
 * )
 *
 * @OA\Schema(
 *     schema="BaseResponse",
 *     description="base response",
 *     @OA\Property(
 *         property="result",
 *         type="object",
 *         title="Result object",
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         title="Error message",
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         @OA\Items(
 *              type="string",
 *          ),
 *         title="Errors array",
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PaginateResponse",
 *     description="paginate response",
 *     @OA\Property(
 *         property="result",
 *         type="object",
 *         title="Result object",
 *         @OA\Property(
 *             property="total",
 *             type="int",
 *             example="10",
 *         ),
 *         @OA\Property(
 *             property="limit",
 *             type="int",
 *             example="10",
 *         ),
 *         @OA\Property(
 *             property="page",
 *             type="int",
 *             example="1",
 *         ),
 *     ),
 * )
 *
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function successResponsePaginator(
        LengthAwarePaginator $paginator,
        AnonymousResourceCollection $result,
        string $itemName = 'items',
    ): JsonResponse {
        return $this->successResponse(
            [
                $itemName => $result,
                'total' => $paginator->total(),
                'limit' => $paginator->perPage(),
                'page'  => $paginator->currentPage(),
            ]
        );
    }

    public function successResponse(
        array $result = [],
        string $message = '',
        int $code = 200
    ): JsonResponse {
        return $this->response(
            result:  $result,
            message: $message,
            code:    $code,
        );
    }

    public function failedResponse(string $message = '', array $errors = [], int $code = 401): JsonResponse
    {
        return $this->response(
            errors:   $errors,
            message: $message,
            code:    $code,
        );
    }

    protected function response(
        array $result = [],
        string $message = '',
        array $errors = [],
        int $code = 200
    ): JsonResponse {
        return new JsonResponse(
            [
                'result' => $result,
                'message' => $message,
                'errors' => $errors,
            ],
            $code
        );
    }
}
