<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\FindAllByUserId;

use Src\Task\Domain\Entity\Task;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\User\Domain\Exception\UserIdNotFoundException;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\User\Application\UseCase\FindById\UserFinderById;

final class TaskAllFinderByUserId
{
    public function __construct(
        private UserFinderById $userFinderById,
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function handle(int $user_id): array
    {
        $id = new UserId($user_id);
        $this->checkUser($user_id);
        $taskEntities = $this->taskRepository->findAllByUserId($id);
        $result = [];
        foreach ($taskEntities as $task) {
            $getTaskDTO = new GetTaskDTO(
                $task->getId()->value(),
                $task->getName()->value(),
                $task->getDescription()->value(),
                $task->getStatus()->value(),
            );
            $getTaskDTO = $this->setUser($getTaskDTO, $task);
            $result[] = $getTaskDTO->toArray();
        }

        return $result;
    }

    private function setUser(GetTaskDTO $getTaskDTO, Task $task): GetTaskDTO
    {
        if ($task->getUser() !== null) {
            $getTaskDTO->setUser([
                'id' => $task->getUser()->getId()->value(),
                'name' => $task->getUser()->getName()->value(),
                'email' => $task->getUser()->getEmail()->value(),
            ]);
        }
        return $getTaskDTO;
    }

    private function checkUser(int $user_id): void
    {
        $user = $this->userFinderById->handle($user_id);
        if (empty($user)) {
            throw new UserIdNotFoundException($user_id);
        }
    }
}
