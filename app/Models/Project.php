<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Project domain model.
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property ProjectStatus $status
 * @property int $budget_cents
 * @property int $hourly_rate_cents
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Client $client
 * @property-read User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<Task> $tasks
 * @property-read \Illuminate\Database\Eloquent\Collection<Note> $notesRelation
 */
class Project extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'status',
        'budget_cents',
        'hourly_rate_cents',
        'start_date',
        'due_date',
        'owner_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ProjectStatus::class,
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Owning client.
     *
     * @return BelongsTo<Client, Project>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Owner user.
     *
     * @return BelongsTo<User, Project>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Related tasks.
     *
     * @return HasMany<Task>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
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
}
