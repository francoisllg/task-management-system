<?php
declare(strict_types=1);

namespace Src\Task\Domain\Interface;

use Src\Task\Domain\Entity\Task;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;

interface TaskRepositoryInterface
{
    public function create(Task $task): Task;

    public function update(Task $task): bool;

    public function delete(TaskId $taskId): bool;

    public function findById(TaskId $taskId): ?Task;

    /**
     * @return Task[]
     */
    public function findAll(): array;

    /**
     * @return Task[]
     */
    public function findAllByUserId(UserId $id): array;
}
