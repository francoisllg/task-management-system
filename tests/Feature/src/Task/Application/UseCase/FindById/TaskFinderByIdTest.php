<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\FindById;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Application\UseCase\FindById\TaskFinderById;

class TaskFinderByIdTest extends TestCase
{
    use RefreshDatabase;

    private TaskCreator $taskCreator;
    private TaskFinderById $taskFinderById;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
        $this->taskFinderById = $this->app->make(TaskFinderById::class);
    }

    /**
     * @test
     */
    public function use_case_can_find_a_task_by_id(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
        );

        $createdTask = $this->taskCreator->handle($createTaskDTO);

        //act
        $taskFound = $this->taskFinderById->handle($createdTask->getId());
        $result = $taskFound->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('user', $result);
    }
}
