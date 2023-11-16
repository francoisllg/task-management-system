<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\Update;

use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\User\Application\DTO\GetUserDTO;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Application\DTO\UpdateTaskDTO;
use Src\Task\Domain\ValueObject\TaskStatus;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\Task\Domain\Exception\TaskNotFoundException;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Application\UseCase\FindById\TaskFinderById;
use Src\User\Application\UseCase\FindById\UserFinderById;

final class TaskUpdater
{
    public function __construct(
        private UserFinderById $userFinderById,
        private TaskFinderById $taskFinderById,
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function handle(UpdateTaskDTO $updateTaskDTO): bool
    {

        $taskData = $this->taskFinderById->handle($updateTaskDTO->getId());
        $this->checkTask($taskData, $updateTaskDTO->getId());
        $task = new Task(new TaskId($taskData->getId()), new TaskName($taskData->getName()), new TaskStatus($taskData->getStatus()), new TaskDescription($taskData->getDescription()));

        if ($updateTaskDTO->getName())$task->setName(new TaskName($updateTaskDTO->getName()));
        if ($updateTaskDTO->getStatus())$task->setStatus(new TaskStatus($updateTaskDTO->getStatus()));
        if ($updateTaskDTO->getDescription())$task->setDescription(new TaskDescription($updateTaskDTO->getDescription()));
        $user = $this->getUser($updateTaskDTO);
        if ($user) $task->setUser($user);

        return $this->taskRepository->update($task);
    }

    private function getUser(UpdateTaskDTO $updateTaskDTO): ?User
    {
        if ($updateTaskDTO->getUserId() > 0) {
            $user = $this->userFinderById->handle($updateTaskDTO->getUserId());
            $this->checkUser($user, $updateTaskDTO->getUserId());

            return new User(
                new UserId($user->getId()),
                new UserName($user->getName()),
                new UserEmail($user->getEmail())
            );
        }
        return null;
    }

    private function checkTask(?GetTaskDTO $task, int $taskId): void
    {
        if (!$task) {
            throw new TaskNotFoundException($taskId);
        }
    }

    private function checkUser(?GetUserDTO $user, int $user_id): void
    {
        if (empty($user)) {
            throw new UserIdNotFoundException($user_id);
        }
    }
}
