<?php

declare(strict_types=1);

namespace Src\User\Application\DTO;

class GetUserDTO
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $email,
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
        ];
    }
}
