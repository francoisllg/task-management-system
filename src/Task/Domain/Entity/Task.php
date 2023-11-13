<?php

declare(strict_types=1);

namespace Src\Task\Domain\Entity;

use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\Task\Domain\ValueObject\TaskName;
use Src\Task\Domain\ValueObject\TaskStatus;
use Src\Task\Domain\ValueObject\TaskDescription;

final class Task
{
    private ?TaskId $id;
    private TaskName $name;
    private TaskStatus $status;
    private ?TaskDescription $description;
    private ?User $user;

    public function __construct(
        ?TaskId $id = null,
        TaskName $name,
        TaskStatus $status,
        ?TaskDescription $description = null,
        ?User $user = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->description = $description;
        $this->user = $user;
    }

    public function getId(): ?TaskId
    {
        return $this->id;
    }

    public function getName(): TaskName
    {
        return $this->name;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getDescription(): ?TaskDescription
    {
        return $this->description;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setId(TaskId $id): void
    {
        $this->id = $id;
    }

    public function setName(TaskName $name): void
    {
        $this->name = $name;
    }

    public function setDescription(TaskDescription $description): void
    {
        $this->description = $description;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        $result = [
            'id' => $this->getId()->value(),
            'name' => $this->getName()->value(),
            'description' => $this->getDescription()->value(),
            'status' => $this->getStatus()->value(),
            'user' => [],
        ];
        if ($this->getUser()) {
            $result['user'] = $this->getUser()->toArray();
        }

        return $result;
    }

}
