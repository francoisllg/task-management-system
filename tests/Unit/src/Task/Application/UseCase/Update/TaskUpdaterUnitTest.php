<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\Update;

use Mockery;
use Tests\TestCase;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\User\Application\DTO\GetUserDTO;
use Src\Task\Application\DTO\UpdateTaskDTO;
use Src\Task\Application\UseCase\Update\TaskUpdater;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Application\UseCase\FindById\TaskFinderById;
use Src\Task\Domain\Exception\InvalidTaskStatusException;
use Src\User\Application\UseCase\FindById\UserFinderById;

class TaskUpdaterUnitTest extends TestCase
{
    /**
     * @test
     */
    public function should_update_a_task(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskUpdater = new TaskUpdater($userFinderById, $taskFinderById, $taskRepository);

        $taskRepository->shouldReceive("update")->once()->andReturn(true);
        $taskFinderById->shouldReceive("handle")->once()->andReturn(
            new GetTaskDTO(
                1,
                'Task 1',
                'Description 1',
                TaskStatusEnum::PENDING->value,
            )
        );

        $updateTaskDTO = new UpdateTaskDTO(
            1,
            'Task 1 updated',
            'Description 1 updated',
            TaskStatusEnum::COMPLETED->value,
        );

        //act
        $result = $taskUpdater->handle($updateTaskDTO);

        //assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function should_update_a_task_with_new_user_id(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskUpdater = new TaskUpdater($userFinderById, $taskFinderById, $taskRepository);

        $taskRepository->shouldReceive("update")->once()->andReturn(true);
        $taskFinderById->shouldReceive("handle")->once()->andReturn(
            new GetTaskDTO(
                1,
                'Task 1',
                'Description 1',
                TaskStatusEnum::PENDING->value,
            )
        );

        $userFinderById->shouldReceive("handle")->once()->andReturn(
            new GetUserDTO(
                1,
                'User 1',
                'user@test.com'
            )
        );

        $updateTaskDTO = new UpdateTaskDTO(
            1,
            'Task 2 updated',
            'Description 2 updated',
            TaskStatusEnum::COMPLETED->value,
            1,
        );

        //act
        $result = $taskUpdater->handle($updateTaskDTO);

        //assert
        $this->assertTrue($result);

    }

    /**
     * @test
     */
    public function should_not_update_a_task_with_invalid_status(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskUpdater = new TaskUpdater($userFinderById, $taskFinderById, $taskRepository);

        $taskFinderById->shouldReceive("handle")->once()->andReturn(
            new GetTaskDTO(
                1,
                'Task 1',
                'Description 1',
                TaskStatusEnum::PENDING->value,
            )
        );

        $updateTaskDTO = new UpdateTaskDTO(
            1,
            'Task 1 updated',
            'Description 1 updated',
            'Invalid status',
        );

        //act//assert
        $this->expectException(InvalidTaskStatusException::class);
        $taskUpdater->handle($updateTaskDTO);
    }

    /**
     * @test
     */
    public function should_not_update_the_task_if_the_user_id_is_invalid(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskUpdater = new TaskUpdater($userFinderById, $taskFinderById, $taskRepository);

        $taskFinderById->shouldReceive("handle")->once()->andReturn(
            new GetTaskDTO(
                1,
                'Task 1',
                'Description 1',
                TaskStatusEnum::PENDING->value,
            )
        );

        $userFinderById->shouldReceive("handle")->once()->andReturn(null);

        $updateTaskDTO = new UpdateTaskDTO(
            1,
            null,
            null,
            null,
            9999,
        );

        //act//assert
        $this->expectException(UserIdNotFoundException::class);
        $taskUpdater->handle($updateTaskDTO);
    }
}
