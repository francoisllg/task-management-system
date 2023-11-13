<?php

declare(strict_types=1);

namespace Tests\Feature\app\Http\Api\V1\Task;

use Tests\TestCase;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();
        $this->seed();
    }
    /** @test */
    public function user_can_create_a_new_task(): void
    {
        //arrange
        $loggedUser = User::find(1);
        $newTaskData = [
            'name' => 'New Task',
            'description' => 'New Task Description',
            'status' => 'pending',
            'user_id' => 1,
        ];

        //act
        $this->actingAs($loggedUser);
        $response = $this->post('/api/v1/tasks', $newTaskData);

        //assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', $newTaskData);
    }

    /** @test */
    public function user_can_update_a_task(): void
    {
        //arrange
        $loggedUser = User::find(1);
        $task = Task::factory()->create();

        $updatedTaskData = [
            'name' => 'Updated Task',
            'description' => 'Updated Task Description',
            'status' => 'completed',
            'user_id' => 1,
        ];

        //act
        $this->actingAs($loggedUser);
        $response = $this->patch("/api/v1/tasks/$task->id", $updatedTaskData);

        //assert
        $response->assertStatus(200);
        $updatedTaskData['id'] = $task->id;
        $this->assertDatabaseHas('tasks', $updatedTaskData);
    }

    /** @test */
    public function user_can_delete_a_task(): void
    {
        //arrange
        $loggedUser = User::find(1);
        $task = Task::factory()->create();

        //act
        $this->actingAs($loggedUser);
        $response = $this->delete("/api/v1/tasks/$task->id");

        //assert
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_retrieve_a_task_by_id(): void
    {
        //arrange
        $loggedUser = User::find(1);
        $task = Task::factory()->create();

        //act
        $this->actingAs($loggedUser);
        $response = $this->get("/api/v1/tasks/$task->id");


        //assert
        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $response['data']);
    }

    /** @test */
    public function user_can_retrieve_all_tasks(): void
    {
        //arrange
        $loggedUser = User::find(1);
        Task::factory()->count(5)->create();

        //act
        $this->actingAs($loggedUser);
        $response = $this->get("/api/v1/tasks");

        //assert
        $response->assertStatus(200);
        $this->assertCount(5, $response['data']);
    }
}
