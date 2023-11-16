<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\FindAll;

use Src\Task\Domain\Entity\Task;
use Src\Task\Application\DTO\GetTaskDTO;
use Src\Task\Domain\Interface\TaskRepositoryInterface;

final class TaskAllFinder
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(): array
    {
        $taskEntities = $this->taskRepository->findAll();
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
}
