<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Task domain model.
 *
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property TaskStatus $status
 * @property int|null $assignee_id
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string $priority
 * @property int $estimate_minutes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Project $project
 * @property-read User|null $assignee
 * @property-read \Illuminate\Database\Eloquent\Collection<Note> $notesRelation
 */
class Task extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'assignee_id',
        'due_date',
        'priority',
        'estimate_minutes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TaskStatus::class,
        'due_date' => 'date',
    ];

    /**
     * Owning project.
     *
     * @return BelongsTo<Project, Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Assigned user (optional).
     *
     * @return BelongsTo<User, Task>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
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
