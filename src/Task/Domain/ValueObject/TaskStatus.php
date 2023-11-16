<?php

declare(strict_types=1);

namespace Src\Task\Domain\ValueObject;

use Src\Task\Domain\Enum\TaskStatusEnum;
use Src\Task\Domain\Exception\InvalidTaskStatusException;

final class TaskStatus
{
    private string $value;

    public function __construct(string $value)
    {
        $this->checkStatus($value);
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self('pending');
    }

    public static function inProgress(): self
    {
        return new self('in_progress');
    }

    public static function completed(): self
    {
        return new self('completed');
    }

    public function value(): string
    {
        return $this->value;
    }

    private function checkStatus(string $status): void
    {
        $cases = array_column(TaskStatusEnum::cases(), 'value');
        if (!in_array($status, $cases)) {
            throw new InvalidTaskStatusException($status);
        }
    }

}
