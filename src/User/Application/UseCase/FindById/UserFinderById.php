<?php

declare(strict_types=1);

namespace Src\User\Application\UseCase\FindById;

use Src\User\Domain\ValueObject\UserId;
use Src\User\Application\DTO\GetUserDTO;
use Src\User\Domain\Interface\UserRepositoryInterface;

class UserFinderById
{

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(int $user_id): ?GetUserDTO
    {
        $id = new UserId($user_id);
        $result = $this->userRepository->findById($id);
        if ($result) {
            return new GetUserDTO(
                $result->getId()->value(),
                $result->getName()->value(),
                $result->getEmail()->value()
            );
        }
        return null;
    }
}
