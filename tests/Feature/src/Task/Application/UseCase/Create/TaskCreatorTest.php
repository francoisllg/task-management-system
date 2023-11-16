<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Create;

use Tests\TestCase;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Application\DTO\CreateTaskDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Domain\Exception\InvalidTaskStatusException;

class TaskCreatorTest extends TestCase
{
    use RefreshDatabase;
    private TaskCreator $taskCreator;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->taskCreator = $this->app->make(TaskCreator::class);
    }

    /**
     * @test
     */
    public function use_case_can_create_a_new_task_with_no_user_related(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
        );

        //act
        $createdTask = $this->taskCreator->handle($createTaskDTO);
        $result = $createdTask->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('user', $result);
    }

    /**
     * @test
     */
    public function use_case_can_create_a_new_task_with_user_related(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 2',
            'Description 2',
            TaskStatusEnum::PENDING->value,
            1,
        );

        //act
        $createdTask = $this->taskCreator->handle($createTaskDTO);
        $result = $createdTask->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('id', $result['user']);
        $this->assertArrayHasKey('name', $result['user']);
        $this->assertArrayHasKey('email', $result['user']);
    }

    /**
     * @test
     */
    public function use_case_can_not_create_a_new_task_with_user_related_if_the_user_does_not_exist(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 3',
            'Description 3',
            TaskStatusEnum::PENDING->value,
            9999,
        );

        //act //assert
        $this->expectException(UserIdNotFoundException::class);
        $this->taskCreator->handle($createTaskDTO);
    }

    /**
     * @test
     */
    public function use_case_can_not_create_a_new_task_if_the_status_is_not_valid(): void
    {
        //arrange
        $createTaskDTO = new CreateTaskDTO(
            'Task 4',
            'Description 4',
            'invalid status',
        );

        //act //assert
        $this->expectException(InvalidTaskStatusException::class);
        $this->taskCreator->handle($createTaskDTO);
    }
}
