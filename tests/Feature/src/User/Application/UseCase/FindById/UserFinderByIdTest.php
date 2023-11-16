<?php

declare(strict_types=1);

namespace Tests\Feature\src\Task\Application\UseCase\Create;

use App\Models\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\User\Application\UseCase\FindById\UserFinderById;

class UserFinderByIdTest extends TestCase
{
    use RefreshDatabase;

    private UserFinderById $userFinderById;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->userFinderById = $this->app->make(UserFinderById::class);
    }

    /**
     * @test
     */
    public function use_case_can_find_an_user_by_id(): void
    {
        //arrange
        $user = User::all()->random();

        //act
        $userFound = $this->userFinderById->handle($user->id);
        $result = $userFound->toArray();

        //assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals($result['id'], $user->id);
        $this->assertEquals($result['name'], $user->name);
        $this->assertEquals($result['email'], $user->email);
    }
}
