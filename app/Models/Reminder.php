<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'note_id',
        'remind_at',
        'description',
        'is_recurring',      // Untuk pengembangan lebih lanjut
        'recurring_frequency', // Untuk pengembangan lebih lanjut
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_recurring' => 'boolean',
        'is_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * Scope a query to only include upcoming reminders.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('is_completed', false)
                     ->where('remind_at', '>=', now())
                     ->orderBy('remind_at', 'asc');
    }

    /**
     * Scope a query to only include past (but not necessarily completed) reminders.
     */
    public function scopePast($query)
    {
        return $query->where('remind_at', '<', now())
                     ->orderBy('remind_at', 'desc');
    }

    /**
     * Scope a query to only include completed reminders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true)
                     ->orderBy('completed_at', 'desc');
    }
}