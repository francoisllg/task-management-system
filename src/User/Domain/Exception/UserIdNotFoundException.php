<?php

declare(strict_types=1);

namespace Src\User\Domain\Exception;

use Exception;

class UserIdNotFoundException extends Exception
{
    public function __construct(int $user_id)
    {
        parent::__construct("User with the ID {$user_id} not found. Unable to continue.", 404);
    }
}
