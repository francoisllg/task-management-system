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
use Src\User\Application\UseCase\FindAll\UserAllFinder;

class UserAllFinderUnitTest extends TestCase
{

    /**
     * @test
     */
    public function should_find_all_users(): void
    {
        //arrange
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userAllFinder = new UserAllFinder($userRepository);

        $userRepository->shouldReceive("findAll")->once()->andReturn([
            new User(
                new UserId(1),
                new UserName('Task 1'),
                new UserEmail('test@test.com'),
            ),
            new User(
                new UserId(2),
                new UserName('User 2'),
                new UserEmail('super@test.com')
            )

        ]);

        //act
        $result = $userAllFinder->handle();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('email', $result[0]);
    }

}
