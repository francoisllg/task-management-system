<?php

declare(strict_types=1);

namespace Src\User\Infraestructure\Repository;

use Src\User\Domain\Entity\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\ValueObject\UserPassword;
use App\Models\User\User as EloquentUserModel;
use Src\User\Domain\Interface\UserRepositoryInterface;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(private EloquentUserModel $model)
    {
    }

    public function findById(UserId $userId): ?User
    {
        $result = $this->model->find($userId->value());

        if ($result) {
            return new User(
                new UserId($result->id),
                new UserName($result->name),
                new UserEmail($result->email),
                new UserPassword($result->password)
            );
        }

        return null;
    }

    public function findAll(): array
    {
        $result = $this->model->all();
        $users = [];
        if (!empty($result)) {
            foreach ($result as $user) {
                $users[] = new User(
                    new UserId($user->id),
                    new UserName($user->name),
                    new UserEmail($user->email),
                    new UserPassword($user->password)
                );
            }
        }
        return $users;
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $result = $this->model->where('email', $email)->first();

        if ($result) {
            return new User(
                new UserId($result->id),
                new UserName($result->name),
                new UserEmail($result->email),
                new UserPassword($result->password)
            );
        }

        return null;
    }

    public function createAccessToken(UserId $userId): string
    {
        $userModel = $this->model->find($userId->value());
        return $userModel->createToken($userModel->email)->plainTextToken;
    }
}
