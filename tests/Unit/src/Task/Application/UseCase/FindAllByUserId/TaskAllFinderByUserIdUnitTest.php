<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\FindAllByUserId;

use Mockery;
use Tests\TestCase;
use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\User\Application\DTO\GetUserDTO;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Domain\ValueObject\TaskStatus;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\User\Application\UseCase\FindById\UserFinderById;
use Src\Task\Application\UseCase\FindAllByUserId\TaskAllFinderByUserId;

class TaskAllFinderByUserIdUnitTest extends TestCase
{

    /**
     * @test
     */
    public function should_find_all_tasks_by_user_id(): void
    {
        //arrange
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskAllFinderByUserId = new TaskAllFinderByUserId($userFinderById, $taskRepository);

        $userFinderById->shouldReceive("handle")->once()->andReturn(
            new GetUserDTO(
                1,
                'User 1',
                'user@test.com',
            )
        );

        $taskRepository->shouldReceive("findAllByUserId")->once()->andReturn([
            new Task(
                new TaskId(1),
                new TaskName('Task 1'),
                new TaskStatus(TaskStatusEnum::PENDING->value),
                new TaskDescription('Description 1'),
                new User(
                    new UserId(1),
                    new UserName('User 1'),
                    new UserEmail('user@test.com')
                )
            ),
            new Task(
                new TaskId(2),
                new TaskName('Task 2'),
                new TaskStatus(TaskStatusEnum::PENDING->value),
                new TaskDescription('Description 2'),
                new User(
                    new UserId(1),
                    new UserName('User 1'),
                    new UserEmail('user@test.com')
                )
            )
        ]);

        //act
        $result = $taskAllFinderByUserId->handle(1);

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
    public function should_not_find_tasks_if_the_user_id_is_invalid(): void
    {
        //arrrange
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $taskAllFinderByUserId = new TaskAllFinderByUserId($userFinderById, $taskRepository);

        $userFinderById->shouldReceive("handle")->once()->andReturn(null);

        //act //assert
        $this->expectException(UserIdNotFoundException::class);
        $taskAllFinderByUserId->handle(9999);

    }
}
