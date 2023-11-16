<?php

declare(strict_types=1);

namespace Src\Task\Application\Service;

use Src\Task\Domain\Enum\TaskStatusEnum;

class TaskStatusService {
    static function getTaskStatuses(): array
    {
        return [
            TaskStatusEnum::PENDING->value,
            TaskStatusEnum::IN_PROGRESS->value,
            TaskStatusEnum::COMPLETED->value,
        ];
    }

    static function getPendingStatus():string
    {
        return TaskStatusEnum::PENDING->value;
    }
}
