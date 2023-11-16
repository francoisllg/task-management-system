<?php

namespace App\Http\Controllers\Api\V1\Task;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use Src\Task\Application\DTO\CreateTaskDTO;
use Src\Task\Application\DTO\UpdateTaskDTO;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use Src\Task\Application\Service\TaskStatusService;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Application\UseCase\Delete\TaskDeleter;
use Src\Task\Application\UseCase\Update\TaskUpdater;
use Src\Task\Application\UseCase\FindAll\TaskAllFinder;
use Src\Task\Application\UseCase\FindById\TaskFinderById;
use Src\Task\Application\UseCase\FindAllByUserId\TaskAllFinderByUserId;

class TaskController extends ApiController
{
    public function __construct(
        private TaskCreator $taskCreator,
        private TaskDeleter $taskDeleter,
        private TaskUpdater $taskUpdater,
        private TaskAllFinder $taskAllFinder,
        private TaskFinderById $taskFinderById,
        private TaskAllFinderByUserId $taskAllFinderByUserId,
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $tasks = $this->taskAllFinder->handle();
            return $this->successResponse($tasks, 'Tasks retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(int $task_id): JsonResponse
    {
        try {
            $task = $this->taskFinderById->handle($task_id);
            $task = !empty($task) ? $task->toArray() : [];
            return $this->successResponse($task, 'Task retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(CreateTaskRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $createTaskDTO = new CreateTaskDTO(
                $validatedData['name'],
                $validatedData['description'] ?? '',
                $validatedData['status'] ?? TaskStatusService::getPendingStatus(),
                $validatedData['user_id'] ?? 0,
            );

            $task = $this->taskCreator->handle($createTaskDTO);
            return $this->successResponse($task->toArray(), 'Task created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }

    public function update(UpdateTaskRequest $request, int $task_id): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $updateTaskDTO = new UpdateTaskDTO(
                $task_id,
                $validatedData['name'] ?? '',
                $validatedData['description'] ?? '',
                $validatedData['status'] ?? '',
                $validatedData['user_id'] ?? 0,
            );

            $result = $this->taskUpdater->handle($updateTaskDTO);
            return $this->successResponse($result, 'Task updated successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(int $task_id): JsonResponse
    {
        try {
            $result = $this->taskDeleter->handle($task_id);
            return $this->successResponse($result, 'Task deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function indexByUserId(int $user_id): JsonResponse
    {
        try {
            $tasks = $this->taskAllFinderByUserId->handle($user_id);
            return $this->successResponse($tasks, 'Task retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
