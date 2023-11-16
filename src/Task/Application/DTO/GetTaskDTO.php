<?php

declare(strict_types=1);

namespace Src\Task\Application\DTO;

class GetTaskDTO
{

    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $description = '',
        private readonly string $status,
        private array $user = [],
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getUser(): array
    {
        return $this->user;
    }

    public function setUser(array $user): void
    {
        $this->user = $user;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'user' => $this->getUser(),
        ];
    }
}
