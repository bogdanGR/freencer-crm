<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @var class-string<Task>
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusCase = $this->faker->randomElement(TaskStatus::cases());
        $status = $statusCase->value;

        $project = $this->randomProjectIdOrFactory();

        $assignee = null;

        if ($this->faker->boolean(80)) {
            $assignee = $this->randomUserIdOrFactory();
        }

        $dueDate = $this->faker->optional()->dateTimeBetween('now', '+2 months');
        $priority = $this->faker->randomElement(['low', 'normal', 'high']);
        $estimate = $this->faker->randomElement([30, 60, 90, 120, 240]);

        $state = [
            'project_id' => $project,
            'title' => $this->faker->sentence(4),
            'status' => $status,
            'assignee_id' => $assignee,
            'due_date' => $dueDate,
            'priority' => $priority,
            'estimate_minutes' => $estimate,
        ];

        return $state;
    }

    /**
     * Pick a random existing project id or fall back to a factory.
     *
     * @return int|Factory
     */
    private function randomProjectIdOrFactory(): int|Factory
    {
        $existingId = Project::query()->inRandomOrder()->value('id');

        if (is_int($existingId)) {
            return $existingId;
        }

        return Project::factory();
    }

    /**
     * Pick a random existing user id or fall back to a factory.
     *
     * @return int|Factory
     */
    private function randomUserIdOrFactory(): int|Factory
    {
        $existingId = User::query()->inRandomOrder()->value('id');

        if (is_int($existingId)) {
            return $existingId;
        }

        return User::factory();
    }
}
