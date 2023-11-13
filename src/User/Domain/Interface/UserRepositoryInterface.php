<?php

declare(strict_types=1);

namespace Src\User\Domain\Interface;

use Src\User\Domain\Entity\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserEmail;

interface UserRepositoryInterface
{
    public function findById(UserId $userId): ?User;
    public function findByEmail(UserEmail $email): ?User;
    public function createAccessToken(UserId $userId): string;
}
