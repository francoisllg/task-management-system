<?php

declare(strict_types=1);

namespace Src\User\Application\UseCase\FindByEmail;

use Src\User\Domain\Entity\User;
use Src\User\Application\DTO\GetUserDTO;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\Interface\UserRepositoryInterface;
use Src\User\Domain\Exception\UserEmailNotFoundException;

class UserFinderByEmail
{

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(string $user_email): ?GetUserDTO
    {
        $email = new UserEmail($user_email);
        $user = $this->userRepository->findByEmail($email);
        $this->checkUser($user, $user_email);
        if ($user) {
            return new GetUserDTO(
                $user->getId()->value(),
                $user->getName()->value(),
                $user->getEmail()->value()
            );
        }
        return null;
    }

    private function checkUser(?User $user, string $email): void
    {
        if (!$user) {
            throw new UserEmailNotFoundException($email);
        }
    }
}
