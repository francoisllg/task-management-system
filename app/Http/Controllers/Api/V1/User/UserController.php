<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use Src\User\Application\UseCase\FindAll\UserAllFinder;
use Src\Task\Application\UseCase\FindAllByUserId\TaskAllFinderByUserId;

class UserController extends ApiController
{
    public function __construct(
        private UserAllFinder $userAllFinder,
        private TaskAllFinderByUserId $taskAllFinderByUserId,

    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->userAllFinder->handle();
            return $this->successResponse($users, 'Users retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
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
