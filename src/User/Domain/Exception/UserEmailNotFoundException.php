<?php

declare(strict_types=1);

namespace Src\User\Domain\Exception;

use Exception;

class UserEmailNotFoundException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct("User with the email {$email} not found. Unable to continue.", 404);
    }
}
