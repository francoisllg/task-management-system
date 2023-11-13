<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\Delete;

use Mockery;
use Tests\TestCase;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\UseCase\Delete\TaskDeleter;
use Src\Task\Domain\Exception\TaskNotFoundException;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\Task\Application\UseCase\FindById\TaskFinderById;

class TaskDeleterUnitTest extends TestCase
{

    /**
     * @test
     */
    public function should_delete_a_task(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $taskDeleter = new TaskDeleter($taskFinderById, $taskRepository);
        $taskFinderById->shouldReceive("handle")->once()->andReturn(
            new GetTaskDTO(
                1,
                'Task 1',
                'Description 1',
                TaskStatusEnum::PENDING->value,
                [],
            )
        );
        $taskRepository->shouldReceive("delete")->once()->andReturn(true);

        //act
        $result = $taskDeleter->handle(1);

        //assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function should_not_delete_a_task_with_invalid_id(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = Mockery::mock(TaskFinderById::class);
        $taskDeleter = new TaskDeleter($taskFinderById, $taskRepository);
        $taskFinderById->shouldReceive("handle")->once()->andReturn(null);
        $task_id = 999999;

        //act //assert
        $this->expectException(TaskNotFoundException::class);
        $taskDeleter->handle($task_id);
    }
}
