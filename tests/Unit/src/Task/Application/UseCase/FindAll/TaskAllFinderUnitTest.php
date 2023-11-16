<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\Create;

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
use Src\Task\Application\UseCase\FindAll\TaskAllFinder;

class TaskAllFinderUnitTest extends TestCase
{

    /**
     * @test
     */
    public function should_find_all_tasks(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskAllFinder = new TaskAllFinder($taskRepository);

        $taskRepository->shouldReceive("findAll")->once()->andReturn([
            new Task(
                new TaskId(1),
                new TaskName('Task 1'),
                new TaskStatus(TaskStatusEnum::PENDING->value),
                new TaskDescription('Description 1'),
                new User(
                    new UserId(1),
                    new UserName('User 1'),
                    new UserEmail('test@test.com')
                ),
            ),
            new Task(
                new TaskId(2),
                new TaskName('Task 2'),
                new TaskStatus(TaskStatusEnum::PENDING->value),
                new TaskDescription('Description 2'),
                new User(
                    new UserId(2),
                    new UserName('User 2'),
                    new UserEmail('super@test.com')
                )
            )
        ]);

        //act
        $result = $taskAllFinder->handle();

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
