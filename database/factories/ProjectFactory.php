<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @var class-string<Project>
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusCase = $this->faker->randomElement(ProjectStatus::cases());
        $status = $statusCase->value;

        $start = $this->faker->optional()->dateTimeBetween('-2 months', '+1 month');
        $due = null;

        if ($start !== null) {
            $due = $this->faker->optional()->dateTimeBetween($start, '+3 months');
        }

        $client = $this->randomClientIdOrFactory();
        $owner = $this->randomUserIdOrFactory();

        $state = [
            'client_id' => $client,
            'name' => $this->faker->sentence(3),
            'status' => $status,
            'budget_cents' => $this->faker->numberBetween(0, 200_000_00),
            'hourly_rate_cents' => $this->faker->numberBetween(2_000, 20_000),
            'start_date' => $start,
            'due_date' => $due,
            'owner_id' => $owner,
        ];

        return $state;
    }

    /**
     * Pick a random existing client id or fall back to a factory.
     *
     * @return int|Factory
     */
    private function randomClientIdOrFactory(): int|Factory
    {
        $existingId = Client::query()->inRandomOrder()->value('id');

        if (is_int($existingId)) {
            return $existingId;
        }

        return Client::factory();
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
