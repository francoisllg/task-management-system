<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Api\V1\User;

use Tests\TestCase;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function user_can_get_all_users(): void
    {
        //arrange
        $loggedUser = User::all()->random();

        //act
        $this->actingAs($loggedUser);
        $response = $this->get('/api/v1/users');
        $result = $response['data'];

        //assert
        $response->assertStatus(200);
        $this->assertCount(11, $result);
    }

    /** @test */
    public function user_can_get_all_tasks_by_user_id(): void
    {
        //arrange

        $loggedUser = User::factory()->create();
        Task::factory(5)->create([
            'user_id' => $loggedUser->id,
        ]);

        //act
        $this->actingAs($loggedUser);
        $response = $this->get("/api/v1/users/$loggedUser->id/tasks");
        $result = $response['data'];

        //assert
        $response->assertStatus(200);
        $this->assertCount(5, $result);
    }
}
