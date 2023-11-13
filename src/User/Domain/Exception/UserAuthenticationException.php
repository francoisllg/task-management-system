<?php

declare(strict_types=1);

namespace Src\User\Domain\Exception;

use Exception;

class UserAuthenticationException extends Exception
{
    public function __construct()
    {
        parent::__construct("The provided password does not match our records.", 400);
    }
}
