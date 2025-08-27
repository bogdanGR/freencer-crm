<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * @var class-string<Client>
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = $this->randomUserIdOrFactory();

        $email = null;
        if ($this->faker->boolean(70)) {
            // If you re-run seeds often, you can reset uniqueness with: $this->faker->unique(true);
            $email = $this->faker->unique()->safeEmail();
        }

        $phone = null;
        if ($this->faker->boolean(50)) {
            $phone = $this->faker->phoneNumber();
        }

        $company = null;
        if ($this->faker->boolean(60)) {
            $company = $this->faker->company();
        }

        $website = null;
        if ($this->faker->boolean(50)) {
            $website = $this->faker->url();
        }

        $notes = null;
        if ($this->faker->boolean(50)) {
            $notes = $this->faker->paragraph();
        }

        $state = [
            'name' => $this->faker->name(),
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'website' => $website,
            'notes' => $notes,
            'owner_id' => $owner,
        ];

        return $state;
    }

    /**
     * Pick a random existing user id or fall back to a factory.
     *
     * @return int|\Illuminate\Database\Eloquent\Factories\Factory
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
