<?php

namespace App\User\Controllers;

use App\Cars\ResourceModels\CarResource;
use App\Common\Requests\PaginateRequest;
use App\Framework\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\User\ResourceModels\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"User"},
     *      path="/api/user",
     *      operationId="current_user",
     *      summary="Get current user",
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             allOf={
     *                @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *                @OA\Schema(
     *                   @OA\Property(
     *                          property="result",
     *                          type="object",
     *                         @OA\Property(
     *                             property="user",
     *                             ref="#/components/schemas/UserResource"
     *                          ),
     *                    )
     *                  ),
     *              },
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     *     )
     */
    public function currentUser()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->successResponse(
            [
                'user' => new UserResource($user),
            ]
        );
    }

    /**
     * @OA\Get(
     *      tags={"User"},
     *      path="/api/user/car",
     *      operationId="current_car",
     *      summary="Get user current car",
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             allOf={
     *                @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *                @OA\Schema(
     *                   @OA\Property(
     *                          property="result",
     *                          type="object",
     *                         @OA\Property(
     *                             property="car",
     *                             nullable=true,
     *                             ref="#/components/schemas/CarResource"
     *                          ),
     *                    )
     *                  ),
     *              },
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     *     )
     */
    public function currentCar()
    {
        /** @var User $user */
        $user = Auth::user();
        $car = Car::whereUserId($user->id)->first();

        return $this->successResponse(
            [
                'car' => $car !== null ? new CarResource($car) : null,
            ]
        );
    }

    /**
     * @OA\Get(
     *      tags={"Users"},
     *      path="/api/users",
     *      operationId="users",
     *      summary="Get users list",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Page items limit",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             allOf={
     *                @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *                @OA\Schema(ref="#/components/schemas/PaginateResponse"),
     *                @OA\Schema(
     *                   @OA\Property(
     *                          property="result",
     *                          type="object",
     *                         @OA\Property(
     *                             property="users",
     *                             type="array",
     *                             @OA\Items(
     *                                  ref="#/components/schemas/UserResource"
     *                             ),
     *                          ),
     *                    )
     *                  ),
     *              },
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      )
     *     )
     */
    public function index(PaginateRequest $request)
    {
        $paginator = User::paginate(
            perPage: $request->getPerPage(),
            page:    $request->getPage(),
        );

        return $this->successResponsePaginator(
            paginator: $paginator,
            result:    UserResource::collection($paginator),
            itemName:  'users',
        );
    }

    /**
     * @OA\Get(
     *      tags={"Users"},
     *      path="/api/users/{user_uuid}",
     *      operationId="user_by_uuid",
     *      summary="Get user by uuid",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="user_uuid",
     *          in="path",
     *          description="User uuid",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *             allOf={
     *                @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *                @OA\Schema(
     *                   @OA\Property(
     *                          property="result",
     *                          type="object",
     *                         @OA\Property(
     *                             property="user",
     *                             ref="#/components/schemas/UserResource"
     *                          ),
     *                    )
     *                  ),
     *              },
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     *     )
     */
    public function getById(Request $request, string $uuid)
    {
        $user = User::whereUuid($uuid)->firstOrFail();

        return $this->successResponse(
            [
                'user' => new UserResource($user),
            ]
        );
    }
}
