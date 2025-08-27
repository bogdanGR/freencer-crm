<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Invoice item (line).
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $description
 * @property int $qty
 * @property int $unit_price_cents
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Invoice $invoice
 */
class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'description',
        'qty',
        'unit_price_cents',
    ];

    /**
     * Owning invoice.
     *
     * @return BelongsTo<Invoice, InvoiceItem>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
