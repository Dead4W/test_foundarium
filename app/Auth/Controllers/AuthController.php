<?php

namespace App\Auth\Controllers;

use App\Auth\Actions\CreateUserTokenAction;
use App\Auth\Actions\RegisterUserAction;
use App\Auth\DTOs\RegisterUserDTO;
use App\Auth\Requests\LoginRequest;
use App\Auth\Requests\RegisterRequest;
use App\Framework\Http\Controllers\Controller;
use App\Models\User;
use App\User\ResourceModels\UserResource;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Response(
 *      response="AuthResponse",
 *      description="Successful operation",
 *      @OA\JsonContent(
 *          allOf={
 *              @OA\Schema(ref="#/components/schemas/BaseResponse"),
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="result",
 *                      type="object",
 *                      @OA\Property(
 *                          property="user",
 *                          ref="#/components/schemas/UserResource"
 *                      ),
 *                      @OA\Property(
 *                          property="access_token",
 *                          type="string",
 *                          example="1|xw52hNtrkR1FGM3PcAPMmf1nhY1esAkJYP56ZIuV",
 *                      ),
 *                  )
 *              ),
 *          },
 *      )
 * ),
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/api/auth/login",
     *      operationId="login",
     *      summary="Login",
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Password",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          ref="#/components/responses/AuthResponse"
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function login(
        LoginRequest $request,
        CreateUserTokenAction $createUserTokerAction,
    ) {
        if (Auth::attempt($request->only(['email', 'password']))) {
            /** @var User $user */
            $user = Auth::user();

            return $this->successResponse(
                [
                    'access_token' => $createUserTokerAction->execute($user),
                    'user' => new UserResource($user),
                ]
            );
        }

        return $this->failedResponse(
            __('auth.failed'),
            [
                'email' => [__('auth.failed')],
            ],
            403
        );
    }

    /**
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/api/auth/register",
     *      operationId="register",
     *      summary="Register",
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Password",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          ref="#/components/responses/AuthResponse"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="User with same email already exists"
     *      )
     *     )
     */
    public function register(
        RegisterRequest $request,
        RegisterUserDTO $registerUserDTO,
        RegisterUserAction $registerUserAction,
        CreateUserTokenAction $createUserTokerAction,
    ) {
        $user = $registerUserAction->execute($registerUserDTO::fromRequest($request));

        return $this->successResponse(
            [
                'access_token' => $createUserTokerAction->execute($user),
                'user' => new UserResource($user),
            ]
        );
    }
}
