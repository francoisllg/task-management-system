<?php

declare(strict_types=1);

namespace Src\User\Application\UseCase\Authenticate;

use Src\User\Domain\Entity\User;
use Illuminate\Support\Facades\Hash;
use Src\User\Application\DTO\GetUserDTO;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\Interface\UserRepositoryInterface;
use Src\User\Domain\Exception\UserEmailNotFoundException;
use Src\User\Domain\Exception\UserAuthenticationException;
use Src\User\Application\UseCase\CreateAccessToken\AccessTokenCreator;

final class UserAuthenticator
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AccessTokenCreator $accessTokenCreator,
    ) {
    }

    public function handle(string $user_email, string $password): array
    {
        $email = new UserEmail($user_email);
        $user = $this->userRepository->findByEmail($email);
        $this->checkUser($user, $user_email);
        $this->checkPassword($user, $password);

        $userDTO = new GetUserDTO(
            $user->getId()->value(),
            $user->getName()->value(),
            $user->getEmail()->value()
        );

        $token = $this->accessTokenCreator->handle($user->getId()->value());

        return [
            'user' => $userDTO->toArray(),
            'token' => $token
        ];
    }

    private function checkUser(?User $user, string $email): void
    {
        if (!$user) {
            throw new UserEmailNotFoundException($email);
        }
    }

    private function checkPassword(User $user, string $password): void
    {
        if (!Hash::check($password, $user->getPassword()->value())) {
            throw new UserAuthenticationException;
        }
    }
}
