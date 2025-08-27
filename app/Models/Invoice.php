<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Invoice domain model.
 *
 * Prices are stored as integer cents to avoid floating point errors.
 *
 * @property int $id
 * @property int $client_id
 * @property string $number
 * @property InvoiceStatus $status
 * @property string $currency
 * @property int $subtotal_cents
 * @property int $tax_cents
 * @property int $total_cents
 * @property \Illuminate\Support\Carbon|null $issued_on
 * @property \Illuminate\Support\Carbon|null $due_on
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection<InvoiceItem> $items
 * @property-read \Illuminate\Database\Eloquent\Collection<Note> $notesRelation
 */
class Invoice extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'number',
        'status',
        'currency',
        'subtotal_cents',
        'tax_cents',
        'total_cents',
        'issued_on',
        'due_on',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => InvoiceStatus::class,
        'issued_on' => 'date',
        'due_on' => 'date',
    ];

    /**
     * Owning client.
     *
     * @return BelongsTo<Client, Invoice>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Line items.
     *
     * @return HasMany<InvoiceItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Attached notes (polymorphic).
     *
     * @return MorphMany<Note>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * Recalculate and persist monetary totals based on current items.
     *
     * @param float $taxRate A decimal tax rate (e.g., 0.24 for 24% VAT).
     *
     * @return void
     */
    public function recalcTotals(float $taxRate = 0.24): void
    {
        $subtotal = (int) $this
            ->items()
            ->get()
            ->sum(static function (InvoiceItem $item): int {
                $lineTotal = $item->qty * $item->unit_price_cents;

                return (int) $lineTotal;
            });

        $tax = (int) round($subtotal * $taxRate);
        $total = $subtotal + $tax;

        $this->subtotal_cents = $subtotal;
        $this->tax_cents = $tax;
        $this->total_cents = $total;

        $this->save();
    }
}
