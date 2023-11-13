<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Task\TaskController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\User\AuthUserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Public routes
Route::post('/v1/login', [AuthUserController::class, 'login']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'v1/tasks'], function () {
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{task_id}', [TaskController::class, 'show']);
    Route::patch('/{task_id}', [TaskController::class, 'update']);
    Route::delete('/{task_id}', [TaskController::class, 'destroy']);
    });

    Route::group(['prefix' => 'v1/users'], function () {
        Route::get('/{user_id}/tasks', [UserController::class, 'indexTasksByUserId']);
    });

});
