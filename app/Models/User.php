<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon; // Untuk accessor umur
use Illuminate\Database\Eloquent\Relations\HasMany; // Untuk notes, labels, reminders

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image_path', // Path gambar profil
        'date_of_birth',      // Tanggal lahir
        // 'role', // Jika Anda memiliki kolom role (misal: 'user', 'admin')
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array // Menggunakan method casts() untuk Laravel 9+
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date', // Cast ke objek Carbon (date saja)
        ];
    }

    /**
     * Accessor untuk mendapatkan umur pengguna.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    /**
     * Cek apakah user adalah admin (contoh sederhana).
     * Sesuaikan dengan logika role Anda.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        // Contoh: jika ada kolom 'role' atau 'is_admin'
        // return $this->role === 'admin';
        // return $this->is_admin;
        return false; // Ganti dengan logika Anda jika ada role admin
    }

    // Relasi ke notes, labels, reminders (jika ada)
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }



    // Relasi untuk kolaborasi (jika ingin dikembangkan)
    public function collaboratedNotes()
    {
        return $this->belongsToMany(Note::class, 'note_collaborators')
            ->withPivot('permission')
            ->withTimestamps();
    }
}
