<?php
declare(strict_types=1);

namespace Src\Task\Domain\Exception;

use Exception;

class InvalidTaskStatusException extends Exception
{
    public function __construct(string $status)
    {
        parent::__construct("Invalid task status {$status}. Unable to continue", 400);
    }
}
