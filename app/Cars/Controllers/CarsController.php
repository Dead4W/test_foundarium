<?php

namespace App\Cars\Controllers;

use App\Cars\Actions\CarLockAction;
use App\Cars\Actions\CarUnlockAction;
use App\Cars\Queries\CarsQuery;
use App\Cars\ResourceModels\CarResource;
use App\Common\Requests\PaginateRequest;
use App\Framework\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CarsController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Cars"},
     *      path="/api/cars",
     *      operationId="cars",
     *      summary="Get cars list",
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
     *                             property="cars",
     *                             type="array",
     *                             @OA\Items(
     *                                  ref="#/components/schemas/CarResource"
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
    public function index(
        PaginateRequest $request,
        CarsQuery $carsQuery
    ) {
        $paginator = $carsQuery
            ->getFreeCarQuery()
            ->paginate(
                perPage: $request->getPerPage(),
                page: $request->getPage(),
            );

        return $this->successResponsePaginator(
            paginator: $paginator,
            result:    CarResource::collection($paginator),
            itemName:  'cars',
        );
    }


    /**
     * @OA\Get(
     *      tags={"Cars"},
     *      path="/api/cars/{car_uuid}",
     *      operationId="car_by_uuid",
     *      summary="Get car by uuid",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="car_uuid",
     *          in="path",
     *          description="Car uuid",
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
     *                             property="car",
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
    public function getById(
        string $uuid,
    ) {
        $car = Car::query()
            ->whereUuid($uuid)
            ->firstOrFail();

        return $this->successResponse(
            [
                'car' => new CarResource($car),
            ]
        );
    }

    /**
     * @OA\Post(
     *      tags={"Cars"},
     *      path="/api/cars/{car_uuid}/lock",
     *      operationId="car_lock_by_uuid",
     *      summary="Lock car by uuid",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="car_uuid",
     *          in="path",
     *          description="Car uuid",
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
     *                             property="status",
     *                             type="boolean",
     *                             example="true",
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
     *          response=404,
     *          description="Not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Can't lock car"
     *      )
     *     )
     */
    public function lock(
        CarLockAction $carLockAction,
        string $uuid,
    ) {
        /** @var User $user */
        $user = Auth::user();
        $car = Car::whereUuid($uuid)->firstOrFail();

        return $this->successResponse(
            [
                'status' => $carLockAction->execute($user, $car),
            ]
        );
    }

    /**
     * @OA\Post(
     *      tags={"Cars"},
     *      path="/api/cars/{car_uuid}/unlock",
     *      operationId="car_unlock_by_uuid",
     *      summary="Unlock car by uuid",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="car_uuid",
     *          in="path",
     *          description="Car uuid",
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
     *                             property="status",
     *                             type="boolean",
     *                             example="true",
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
     *          response=404,
     *          description="Not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Can't access car to unlock"
     *      )
     *     )
     */
    public function unlock(
        CarUnlockAction $carUnlockAction,
        string $uuid,
    ) {
        /** @var User $user */
        $user = Auth::user();
        $car = Car::whereUuid($uuid)->firstOrFail();

        return $this->successResponse(
            [
                'status' => $carUnlockAction->execute($user, $car),
            ]
        );
    }
}
