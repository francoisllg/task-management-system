<?php

declare(strict_types=1);
namespace Src\User\Application\UseCase\CreateAccessToken;

use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\Interface\UserRepositoryInterface;

final class AccessTokenCreator
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(int $user_id): string
    {
        $id = new UserId($user_id);
        return $this->userRepository->createAccessToken($id);
    }
}
