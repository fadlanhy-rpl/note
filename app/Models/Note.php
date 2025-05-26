<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'color',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The labels that belong to the note.
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class); // Laravel akan mengasumsikan 'label_note'
        // atau secara eksplisit: return $this->belongsToMany(Label::class, 'label_note');
    }

    /**
     * The users that collaborate on this note.
     */
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'note_collaborators')
            ->withPivot('permission')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include non-archived notes.
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope a query to only include archived notes.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    // Helper untuk mendapatkan reminder aktif (belum selesai dan akan datang)
    public function activeReminder()
    {
        return $this->reminders()
            ->where('is_completed', false)
            ->where('remind_at', '>=', now())
            ->orderBy('remind_at', 'asc')
            ->first();
    }
}
