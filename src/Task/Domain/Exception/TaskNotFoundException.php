<?php
declare(strict_types=1);

namespace Src\Task\Domain\Exception;

use Exception;

class TaskNotFoundException extends Exception
{
    public function __construct(int $task_id)
    {
        parent::__construct("Task with id {$task_id} not found", 404);
    }
}
