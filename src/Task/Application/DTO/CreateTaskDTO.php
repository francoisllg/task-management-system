<?php

declare(strict_types=1);

namespace Src\Task\Application\DTO;

use Src\Task\Domain\Enum\TaskStatusEnum;

class CreateTaskDTO
{

    public function __construct(
        private readonly string $name,
        private readonly string $description = '',
        private readonly string $status = TaskStatusEnum::PENDING->value,
        private readonly int $user_id = 0,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

}
