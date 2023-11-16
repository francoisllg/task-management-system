<?php

declare(strict_types=1);

namespace Src\User\Domain\Entity;

use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\ValueObject\UserPassword;

final class User
{
    public function __construct(
        private UserId $id,
        private UserName $name,
        private UserEmail $email,
        private ?UserPassword $password = null
    ) {
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): ?UserPassword
    {
        return $this->password;
    }

    public function setId(UserId $id): void
    {
        $this->id = $id;
    }

    public function setName(UserName $name): void
    {
        $this->name = $name;
    }

    public function setEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function toArray(): array
    {
        $user = [
            'id' => $this->getId()->value(),
            'name' => $this->getName()->value(),
            'email' => $this->getEmail()->value(),
        ];
        if ($this->getPassword()) {
            $user['password'] = $this->getPassword()->value();
        }

        return $user;
    }
}

