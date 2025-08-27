<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Note (polymorphic) attached to various entities.
 *
 * @property int $id
 * @property string $notable_type
 * @property int $notable_id
 * @property string $body
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Model $notable
 * @property-read User $author
 */
class Note extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'body',
        'user_id',
        // note: notable_type and notable_id are set via morphs, usually not mass-filled directly
    ];

    /**
     * The parent notable model.
     *
     * @return MorphTo
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Authoring user.
     *
     * @return BelongsTo<User, Note>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
