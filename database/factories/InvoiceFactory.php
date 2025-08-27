<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * @var class-string<Invoice>
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issued = $this->faker->optional()->dateTimeBetween('-2 months', 'now');
        $due = null;

        if ($issued !== null) {
            $due = (clone $issued)->modify('+14 days');
        }

        $client = $this->randomClientIdOrFactory();

        $statusCase = $this->faker->randomElement(InvoiceStatus::cases());
        $status = $statusCase->value;

        $number = $this->generateInvoiceNumber();

        $state = [
            'client_id' => $client,
            'number' => $number,
            'status' => $status,
            'currency' => 'EUR',
            'subtotal_cents' => 0,
            'tax_cents' => 0,
            'total_cents' => 0,
            'issued_on' => $issued,
            'due_on' => $due,
        ];

        return $state;
    }

    /**
     * Configure the factory callbacks.
     *
     * @return static
     */
    public function configure(): static
    {
        $factory = $this->afterCreating(function (Invoice $invoice): void {
            $minItems = 2;
            $maxItems = 5;
            $count = $this->faker->numberBetween($minItems, $maxItems);

            $this->createItemsForInvoice($invoice, $count);

            $invoice->recalcTotals(0.24);
        });

        return $factory;
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
     * Create a human-friendly invoice number.
     *
     * @return string
     */
    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $seq = $this->faker->unique()->numerify('####');
        $number = 'INV-' . $year . '-' . $seq;

        return $number;
    }

    /**
     * Create several line items for the given invoice.
     *
     * @param Invoice $invoice
     * @param int $count
     *
     * @return void
     */
    private function createItemsForInvoice(Invoice $invoice, int $count): void
    {
        $i = 0;

        while ($i < $count) {
            InvoiceItem::factory()->create([
                'invoice_id' => $invoice->id,
            ]);

            $i++;
        }
    }
}
