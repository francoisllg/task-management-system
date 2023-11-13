<?php

declare(strict_types=1);

namespace Src\Task\Application\DTO;


class UpdateTaskDTO
{
    public function __construct(
        private readonly int $id,
        private readonly ?string $name = '',
        private readonly ?string $description = '',
        private readonly ?string $status = '',
        private readonly ?int $user_id = 0,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }
}
