<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use Src\Task\Application\UseCase\FindAllByUserId\TaskAllFinderByUserId;

class UserController extends ApiController
{
    public function __construct(
        private TaskAllFinderByUserId $taskAllFinderByUserId,
    ) {
    }

    public function indexTasksByUserId(int $user_id): JsonResponse
    {
        try {
            $tasks = $this->taskAllFinderByUserId->handle($user_id);
            return $this->successResponse($tasks, 'User tasks retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
