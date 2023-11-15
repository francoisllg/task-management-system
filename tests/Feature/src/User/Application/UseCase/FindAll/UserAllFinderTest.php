<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Create;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\User\Application\UseCase\FindAll\UserAllFinder;

class UserAllFinderTest extends TestCase
{
    use RefreshDatabase;
    private UserAllFinder $userAllFinder;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->userAllFinder = $this->app->make(UserAllFinder::class);
    }

    /**
     * @test
     */
    public function use_case_can_find_all_users(): void
    {
        //act
        $result = $this->userAllFinder->handle();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(11, $result);
        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('email', $result[0]);
    }

}
