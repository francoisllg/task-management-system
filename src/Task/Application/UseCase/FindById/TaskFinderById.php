<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\FindById;

use Src\Task\Domain\Entity\Task;
use Src\Task\Domain\ValueObject\TaskId;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\Task\Domain\Interface\TaskRepositoryInterface;

class TaskFinderById
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(int $task_id): ?GetTaskDTO
    {
        $result = null;
        $id = new TaskId($task_id);
        $task = $this->taskRepository->findById($id);

        if ($task) {
            $result = new GetTaskDTO(
                $task->getId()->value(),
                $task->getName()->value(),
                $task->getDescription()->value(),
                $task->getStatus()->value(),
            );
            $result = $this->setUser($result, $task);
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
}
