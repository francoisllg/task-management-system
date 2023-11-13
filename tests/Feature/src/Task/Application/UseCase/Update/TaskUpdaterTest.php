<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Update;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Src\Task\Application\DTO\UpdateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Application\UseCase\Update\TaskUpdater;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Domain\Exception\InvalidTaskStatusException;

class TaskUpdaterTest extends TestCase
{
    use RefreshDatabase;
    private TaskCreator $taskCreator;
    private TaskUpdater $taskUpdater;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
        $this->taskUpdater = $this->app->make(TaskUpdater::class);
    }

    /**
     * @test
     */
    public function use_case_can_update_a_task(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        $updateTaskDTO = new UpdateTaskDTO(
            $createdTask->getId(),
            'Task 1 updated',
            'Description 1 updated',
            TaskStatusEnum::COMPLETED->value,
        );

        //act
        $result = $this->taskUpdater->handle($updateTaskDTO);

        //assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $createdTask->getId(),
            'name' => $updateTaskDTO->getName(),
            'description' => $updateTaskDTO->getDescription(),
            'status' => $updateTaskDTO->getStatus(),
        ]);
    }

    /**
     * @test
     */
    public function use_case_can_update_a_task_with_user_related(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 2',
            'Description 2',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        $updateTaskDTO = new UpdateTaskDTO(
            $createdTask->getId(),
            'Task 2 updated',
            'Description 2 updated',
            TaskStatusEnum::COMPLETED->value,
            1,
        );

        //act
        $result = $this->taskUpdater->handle($updateTaskDTO);

        //assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $createdTask->getId(),
            'name' => $updateTaskDTO->getName(),
            'description' => $updateTaskDTO->getDescription(),
            'status' => $updateTaskDTO->getStatus(),
            'user_id' => $updateTaskDTO->getUserId(),
        ]);
    }

    /**
     * @test
     */
    public function use_case_can_not_update_a_task_with_invalid_status(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 3',
            'Description 3',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        $updateTaskDTO = new UpdateTaskDTO(
            $createdTask->getId(),
            'Task 3 updated',
            'Description 3 updated',
            'invalid status',
        );

        //act//assert
        $this->expectException(InvalidTaskStatusException::class);
        $this->taskUpdater->handle($updateTaskDTO);
    }

    /**
     * @test
     */
    public function use_case_can_not_update_a_task_with_user_related_if_the_user_does_not_exist(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 4',
            'Description 4',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        $updateTaskDTO = new UpdateTaskDTO(
            $createdTask->getId(),
            'Task 4 updated',
            'Description 4 updated',
            TaskStatusEnum::COMPLETED->value,
            9999,
        );

        //act//assert
        $this->expectException(UserIdNotFoundException::class);
        $this->taskUpdater->handle($updateTaskDTO);
    }
}
