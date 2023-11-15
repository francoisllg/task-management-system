<?php

declare(strict_types=1);

namespace Src\User\Application\UseCase\FindAll;

use Src\User\Application\DTO\GetUserDTO;
use Src\User\Domain\Interface\UserRepositoryInterface;

final class UserAllFinder
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(): array
    {
        $userEntities = $this->userRepository->findAll();
        $result = [];
        foreach ($userEntities as $user) {
            $getUserDTO = new GetUserDTO(
                $user->getId()->value(),
                $user->getName()->value(),
                $user->getEmail()->value(),
            );
            $result[] = $getUserDTO->toArray();
        }

        return $result;
    }
}
