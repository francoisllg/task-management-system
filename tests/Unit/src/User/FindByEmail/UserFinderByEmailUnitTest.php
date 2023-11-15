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
use Src\User\Application\UseCase\FindByEmail\UserFinderByEmail;

class UserFinderByEmailUnitTest extends TestCase
{
    /**
     * @test
     */
    public function should_find_an_user_by_email(): void
    {
        //arrange
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userFinderByEmail = new UserFinderByEmail($userRepository);

        $userRepository->shouldReceive("findByEmail")->once()->andReturn(
            new User(
                new UserId(1),
                new UserName('User 1'),
                new UserEmail('email@email.com')
            )
        );

        //act
        $userFound = $userFinderByEmail->handle('email@email.com');
        $result = $userFound->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('email', $result);
    }
}
