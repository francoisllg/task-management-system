<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Create;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Application\UseCase\FindAllByUserId\TaskAllFinderByUserId;

class TaskAllFinderByUserIdTest extends TestCase
{
    use RefreshDatabase;
    private TaskCreator $taskCreator;
    private TaskAllFinderByUserId $taskAllFinderByUserId;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
        $this->taskAllFinderByUserId = $this->app->make(TaskAllFinderByUserId::class);
    }

    /**
     * @test
     */
    public function use_case_can_find_all_tasks_by_user_id(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
            10,
        );

        $this->taskCreator->handle($createTaskDTO);

        $createTaskDTO = new CreateTaskDTO(
            'Task 2',
            'Description 2',
            TaskStatusEnum::PENDING->value,
            10,
        );

        $this->taskCreator->handle($createTaskDTO);

        //act
        $result = $this->taskAllFinderByUserId->handle(10);

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

    /**
     * @test
     */
    public function use_case_cannot_find_tasks_if_the_user_id_is_not_valid(): void
    {
        //act //assert
        $this->expectException(UserIdNotFoundException::class);
        $this->taskAllFinderByUserId->handle(9999);

    }
}
