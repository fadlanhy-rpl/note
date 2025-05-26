<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate; // Tidak digunakan langsung di sini, tapi bisa jika ada Gate kustom
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Note;       // Import Model Note
use App\Policies\NotePolicy; // Import NotePolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Contoh bawaan
        Note::class => NotePolicy::class, // Daftarkan NotePolicy untuk Model Note
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Metode ini akan mendaftarkan policies di atas

        // Jika Anda memiliki Gate kustom (tidak kita gunakan untuk Note saat ini,
        // karena kita menggunakan Policy), Anda bisa mendaftarkannya di sini.
        // Contoh:
        // Gate::define('edit-settings', function (User $user) {
        //     return $user->isAdmin();
        // });
    }
}