<?php

declare(strict_types=1);

namespace Tests\Unit\src\Task\Application\UseCase\Create;

use Mockery;
use Tests\TestCase;
use Src\User\Domain\Entity\User;
use Src\User\Domain\ValueObject\UserId;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\User\Domain\Interface\UserRepositoryInterface;
use Src\User\Application\UseCase\FindById\UserFinderById;

class UserFinderByIdUnitTest extends TestCase
{
    /**
     * @test
     */
    public function should_find_an_user_by_id(): void
    {
        //arrange
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userFinderById = new UserFinderById($userRepository);

        $userRepository->shouldReceive("findById")->once()->andReturn(
            new User(
                new UserId(1),
                new UserName('User 1'),
                new UserEmail('email@email.com')
            )
        );

        //act
        $userFound = $userFinderById->handle(1);
        $result = $userFound->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('email', $result);
    }
}
