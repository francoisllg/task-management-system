<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\Create;

use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\User\Application\DTO\GetUserDTO;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Application\DTO\CreateTaskDTO;
use Src\Task\Domain\ValueObject\TaskStatus;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Application\UseCase\FindById\UserFinderById;

final class TaskCreator
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private UserFinderById $userFinderById
    ) {
    }

    public function handle(CreateTaskDTO $createTaskDTO): GetTaskDTO
    {
        $result = [];
        $task = $this->buildTask($createTaskDTO);
        $user = $this->getUser($createTaskDTO);
        if ($user) $task->setUser($user);

        $createdTask = $this->taskRepository->create($task);
        if ($createdTask) $result = $this->formatTask($createdTask);
        return $result;
    }

    private function buildTask(CreateTaskDTO $createTaskDTO): Task
    {
        return new Task(
            null,
            new TaskName($createTaskDTO->getName()),
            new TaskStatus($createTaskDTO->getStatus()),
            new TaskDescription($createTaskDTO->getDescription())
        );
    }

    private function getUser(CreateTaskDTO $createTaskDTO): ?User
    {
        if ($createTaskDTO->getUserId() > 0) {
            $user = $this->userFinderById->handle($createTaskDTO->getUserId());
            $this->checkUser($user, $createTaskDTO->getUserId());
            return new User(
                new UserId($user->getId()),
                new UserName($user->getName()),
                new UserEmail($user->getEmail()),
            );
        }
        return null;
    }

    private function formatTask(Task $task): GetTaskDTO
    {
        $taskArray = $task->toArray();
        $getTaskDTO = new GetTaskDTO(
            (int) $taskArray['id'],
            $taskArray['name'],
            $taskArray['description'],
            $taskArray['status'],
            (array) $taskArray['user']
        );

        return $getTaskDTO;
    }

    private function checkUser(?GetUserDTO $user, int $user_id): void
    {
        if (empty($user)) {
            throw new UserIdNotFoundException($user_id);
        }
    }
}
