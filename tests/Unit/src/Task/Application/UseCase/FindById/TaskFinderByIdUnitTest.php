<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\FindById;

use Mockery;
use Tests\TestCase;
use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Domain\ValueObject\TaskStatus;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\Task\Application\UseCase\FindById\TaskFinderById;

class TaskFinderByIdUnitTest extends TestCase
{
    /**
     * @test
     */
    public function should_find_a_task_by_id(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskFinderById = new TaskFinderById($taskRepository);

        $taskRepository->shouldReceive("findById")->once()->andReturn(
            new Task(
                new TaskId(1),
                new TaskName('Task 1'),
                new TaskStatus(TaskStatusEnum::PENDING->value),
                new TaskDescription('Description 1'),
                new User(
                    new UserId(1),
                    new UserName('User 1'),
                    new UserEmail('email@email.com')
                )
            )
        );

        //act
        $taskFound = $taskFinderById->handle(1);
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
