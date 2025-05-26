<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    /**
     * Get the user that owns the label.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The notes that belong to the label.
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class); // Laravel akan mengasumsikan 'label_note'
    }
}
