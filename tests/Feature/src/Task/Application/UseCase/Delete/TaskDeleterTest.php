<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Delete;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Application\UseCase\Delete\TaskDeleter;
use Src\Task\Domain\Exception\TaskNotFoundException;

class TaskDeleterTest extends TestCase
{
    use RefreshDatabase;
    private TaskCreator $taskCreator;
    private TaskDeleter $taskDeleter;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
        $this->taskDeleter = $this->app->make(TaskDeleter::class);
    }

    /**
     * @test
     */
    public function use_case_can_delete_a_task(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        //act
        $result = $this->taskDeleter->handle($createdTask->getId());

        //assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', [
            'id' => $createdTask->getId(),
        ]);
    }

    /**
     * @test
     */
    public function use_case_can_not_delete_a_task_with_invalid_id(): void
    {
        //arrange
        $task_id = 999999;

        //act //assert
        $this->expectException(TaskNotFoundException::class);
        $this->taskDeleter->handle($task_id);
    }
}
