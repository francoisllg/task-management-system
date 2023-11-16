<?php

declare(strict_types=1);

namespace Src\Task\Domain\Enum;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
