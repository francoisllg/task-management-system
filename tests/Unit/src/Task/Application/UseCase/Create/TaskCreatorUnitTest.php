<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\Create;


use Mockery;
use Tests\TestCase;
use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\User\Application\DTO\GetUserDTO;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Application\DTO\CreateTaskDTO;
use Src\Task\Domain\ValueObject\TaskStatus;
use App\Models\User\User as EloquentUserModel;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\Task\Application\UseCase\Create\TaskCreator;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Domain\Exception\InvalidTaskStatusException;
use Src\User\Application\UseCase\FindById\UserFinderById;

class TaskCreatorUnitTest extends TestCase
{

    /**
     * @test
     */
    public function should_create_a_new_task(): void
    {
        //arrange
        $newUserData = EloquentUserModel::factory()->make();
        $newUserDTO = new GetUserDTO(
            1,
            $newUserData->name,
            $newUserData->email,
        );

        $newTask = new Task(
            new TaskId(1),
            new TaskName('Task 1'),
            new TaskStatus(TaskStatusEnum::PENDING->value),
            new TaskDescription('Description 1'),
            new User(
                new UserId($newUserDTO->getId()),
                new UserName($newUserDTO->getName()),
                new UserEmail($newUserDTO->getEmail()),
            )
        );

        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskCreator = new TaskCreator($taskRepository, $userFinderById);

        $userFinderById->shouldReceive("handle")->once()->andReturn($newUserDTO);
        $taskRepository->shouldReceive("create")->once()->andReturn($newTask);

        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
            1,
        );

        //act
        $createdTask = $taskCreator->handle($createTaskDTO);

        //assert
        $this->assertIsObject($createdTask);
        $this->assertInstanceOf(GetTaskDTO::class, $createdTask);
        $this->assertEquals($createdTask->getId(), $newTask->getId()->value());
        $this->assertEquals($createdTask->getName(), $newTask->getName()->value());
        $this->assertEquals($createdTask->getDescription(), $newTask->getDescription()->value());
        $this->assertEquals($createdTask->getStatus(), $newTask->getStatus()->value());
        $this->assertEquals($createdTask->getUser()['id'], $newTask->getUser()->getId()->value());
    }

    /**
     * @test
     */
    public function should_create_a_new_task_with_user_related(): void
    {
        //arrange
        $newUserData = EloquentUserModel::factory()->make();
        $newUserDTO = new GetUserDTO(
            1,
            $newUserData->name,
            $newUserData->email,
        );

        $newTask = new Task(
            new TaskId(1),
            new TaskName('Task 1'),
            new TaskStatus(TaskStatusEnum::PENDING->value),
            new TaskDescription('Description 1'),
            new User(
                new UserId($newUserDTO->getId()),
                new UserName($newUserDTO->getName()),
                new UserEmail($newUserDTO->getEmail()),
            )
        );

        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskCreator = new TaskCreator($taskRepository, $userFinderById);

        $userFinderById->shouldReceive("handle")->once()->andReturn($newUserDTO);
        $taskRepository->shouldReceive("create")->once()->andReturn($newTask);

        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
            1,
        );

        //act
        $createdTask = $taskCreator->handle($createTaskDTO);
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
    public function should_not_create_a_new_task_if_the_user_id_is_not_valid(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskCreator = new TaskCreator($taskRepository, $userFinderById);
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            TaskStatusEnum::PENDING->value,
            9999,
        );

        $userFinderById->shouldReceive("handle")->once()->andThrow(new UserIdNotFoundException($createTaskDTO->getUserId()));

        //act //assert
        $this->expectException(UserIdNotFoundException::class);
        $taskCreator->handle($createTaskDTO);
    }

    /**
     * @test
     */
    public function should_not_create_a_new_task_if_the_status_is_not_valid(): void
    {
        //arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $userFinderById = Mockery::mock(UserFinderById::class);
        $taskCreator = new TaskCreator($taskRepository, $userFinderById);
        $createTaskDTO = new CreateTaskDTO(
            'Task 1',
            'Description 1',
            'Status invalid',
        );

        //act //assert
        $this->expectException(InvalidTaskStatusException::class);
        $taskCreator->handle($createTaskDTO);
    }
}
