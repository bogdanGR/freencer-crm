<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * @var class-string<InvoiceItem>
     */
    protected $model = InvoiceItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoice = $this->randomInvoiceIdOrFactory();

        $state = [
            'invoice_id' => $invoice,
            'description' => $this->faker->sentence(3),
            'qty' => $this->faker->numberBetween(1, 10),
            'unit_price_cents' => $this->faker->numberBetween(5_00, 2_000_00),
        ];

        return $state;
    }

    /**
     * Pick a random existing invoice id or fall back to a factory.
     *
     * @return int|Factory
     */
    private function randomInvoiceIdOrFactory(): int|Factory
    {
        $existingId = Invoice::query()->inRandomOrder()->value('id');

        if (is_int($existingId)) {
            return $existingId;
        }

        return Invoice::factory();
    }
}
