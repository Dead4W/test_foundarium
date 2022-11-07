<?php

use App\Auth\Controllers\AuthController;
use App\User\Controllers\UserController;
use App\Cars\Controllers\CarsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::apiResource('Auth', 'AuthController');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'currentUser']);
        Route::get('car', [UserController::class, 'currentCar']);
    });

    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::get('{uuid}', [UserController::class, 'getById']);
    });

    Route::prefix('cars')->group(function () {
        Route::get('', [CarsController::class, 'index']);
        Route::get('{uuid}', [CarsController::class, 'getById']);
        Route::post('{uuid}/lock', [CarsController::class, 'lock']);
        Route::post('{uuid}/unlock', [CarsController::class, 'unlock']);
    });
});
