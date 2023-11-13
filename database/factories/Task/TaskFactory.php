<?php

namespace Database\Factories\Task;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Task\Application\Service\TaskStatusService;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(TaskStatusService::getTaskStatuses()),
            'user_id' => User::all()->random()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
