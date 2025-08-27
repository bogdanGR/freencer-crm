<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * @var class-string<Note>
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $author = $this->randomUserIdOrFactory();

        $state = [
            'body' => $this->faker->sentences(2, true),
            'user_id' => $author,
            // notable_type/notable_id set via state() or forNotable()
        ];

        return $state;
    }

    /**
     * Attach this note to a notable model.
     *
     * @param Model $model
     *
     * @return static
     */
    public function forNotable(Model $model): static
    {
        $factory = $this->state(function () use ($model): array {
            $state = [
                'notable_type' => $model::class,
                'notable_id' => $model->getKey(),
            ];

            return $state;
        });

        return $factory;
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
