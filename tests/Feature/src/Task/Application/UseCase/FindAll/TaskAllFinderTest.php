<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Create;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Application\UseCase\FindAll\TaskAllFinder;

class TaskAllFinderTest extends TestCase
{
    use RefreshDatabase;
    private TaskCreator $taskCreator;
    private TaskAllFinder $taskAllFinder;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
        $this->taskAllFinder = $this->app->make(TaskAllFinder::class);
    }

    /**
     * @test
     */
    public function use_case_can_find_all_tasks(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
        );

        $this->taskCreator->handle($createTaskDTO);

        $createTaskDTO = new CreateTaskDTO(
            'Task 2',
            'Description 2',
            TaskStatusEnum::PENDING->value,
        );

        $this->taskCreator->handle($createTaskDTO);

        //act
        $result = $this->taskAllFinder->handle();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
        $this->assertArrayHasKey('status', $result[0]);
        $this->assertArrayHasKey('user', $result[0]);
    }

}
