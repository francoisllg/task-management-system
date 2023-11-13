<?php

declare(strict_types=1);

namespace Src\Task\Application\UseCase\Delete;

use Src\Task\Domain\ValueObject\TaskId;
use Src\Task\Domain\Exception\TaskNotFoundException;
use Src\Task\Domain\Interface\TaskRepositoryInterface;
use Src\Task\Application\UseCase\FindById\TaskFinderById;

final class TaskDeleter
{
    public function __construct(
        private TaskFinderById $taskFinderById,
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function handle(int $task_id): bool
    {
        $id = new TaskId($task_id);
        $this->checkTask($task_id);
        return $this->taskRepository->delete($id);
    }


    private function checkTask(int $task_id): void
    {
        $task = $this->taskFinderById->handle($task_id);
        if (!$task) {
            throw new TaskNotFoundException($task_id);
        }
    }

}
