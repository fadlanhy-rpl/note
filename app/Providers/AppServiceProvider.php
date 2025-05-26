<?php

namespace App\Providers;

// Import Facades dan Model yang diperlukan
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User; // Untuk type hinting jika diperlukan
use App\Models\Label; // Untuk relasi User

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Method ini digunakan untuk me-binding berbagai service ke dalam service container Laravel.
     * Untuk kasus kita saat ini, tidak ada service custom yang perlu diregistrasikan di sini.
     *
     * @return void
     */
    public function register(): void
    {
        // Tidak ada registrasi service khusus untuk saat ini.
    }

    /**
     * Bootstrap any application services.
     *
     * Method ini dipanggil setelah semua service provider lain diregistrasikan.
     * Ini adalah tempat yang baik untuk mendaftarkan event listeners, view composers,
     * atau melakukan booting fungsionalitas lain yang dibutuhkan aplikasi.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * View Composer untuk data global aplikasi utama.
         *
         * View Composer ini akan menyediakan variabel-variabel umum ke semua view
         * yang terdaftar dalam array pertama. Ini membantu menjaga controller tetap bersih
         * dan memastikan data konsisten di berbagai bagian tampilan.
         *
         * Variabel yang di-pass:
         * - $currentUser: Objek User yang sedang login (null jika guest).
         * - $labelsForSidebar: Koleksi Label milik user untuk ditampilkan di sidebar (di-cache).
         * - $allUserLabels: Semua Label milik user (di-cache, berguna untuk dropdown/modals).
         * - $notesCount: Jumlah catatan aktif milik user.
         * - $labelsCount: Jumlah total label milik user.
         * - $sharedCount: Placeholder untuk jumlah item yang di-share (bisa dikembangkan).
         * - $currentTheme: Preferensi tema user ('light', 'dark', atau 'system').
         * - $noteColors: Array warna yang tersedia untuk catatan (dari config).
         * - $pageTitle: Judul halaman default jika tidak di-set oleh controller/view.
         */
        View::composer([
            'layouts.app',                  // Layout utama aplikasi
            'partials.*',                   // Semua file di dalam direktori partials
            'notes.*',                      // Semua file di dalam direktori notes
            'labels.*',                     // Semua file di dalam direktori labels
            'reminders.*',                  // Semua file di dalam direktori reminders
            'settings.*',                   // Semua file di dalam direktori settings
            'profile.*',                    // Semua file di dalam direktori profile
        ], function ($view) {

            // 1. Inisialisasi variabel dengan nilai default
            // Ini penting untuk mencegah error "Undefined variable" jika view dirender
            // dalam konteks di mana user belum login atau data spesifik tidak ada.
            $view->with('currentUser', null);
            $view->with('labelsForSidebar', collect()); // Koleksi Eloquent kosong
            $view->with('allUserLabels', collect());    // Koleksi Eloquent kosong
            $view->with('notesCount', 0);
            $view->with('labelsCount', 0);
            $view->with('sharedCount', 0);              // Placeholder
            $view->with('currentTheme', config('app.default_theme', 'system')); // Ambil dari config atau default ke 'system'

            // 2. Sediakan $noteColors dari file konfigurasi
            // Pastikan file config/notes.php ada dan berisi array 'colors'.
            $noteColorsFromConfig = config('notes.colors');
            $view->with('noteColors', is_array($noteColorsFromConfig) ? $noteColorsFromConfig : []);

            // 3. Sediakan $pageTitle default
            // Controller atau view spesifik bisa meng-override ini.
            if (!$view->offsetExists('pageTitle')) {
                $view->with('pageTitle', config('app.name', 'MyNotes')); // Gunakan nama aplikasi atau default
            }

            // 4. Sediakan data spesifik pengguna HANYA jika pengguna sudah login
            if (Auth::check()) {
                /** @var \App\Models\User $user */ // Type hinting untuk auto-completion di IDE
                $user = Auth::user();
                $view->with('currentUser', $user);

                if ($user) { // Pengecekan tambahan untuk memastikan objek $user valid
                    // Ambil dan cache daftar label untuk sidebar (misalnya, 15 menit)
                    $labelsForSidebar = Cache::remember(
                        'user_sidebar_labels_' . $user->id, // Kunci cache unik per user
                        now()->addMinutes(15), // Durasi cache
                        function () use ($user) {
                            return $user->labels()->orderBy('name')->take(10)->get();
                        }
                    );
                    $view->with('labelsForSidebar', $labelsForSidebar);

                    // Ambil dan cache semua label pengguna
                    $allUserLabels = Cache::remember(
                        'user_all_labels_' . $user->id,
                        now()->addMinutes(15),
                        function () use ($user) {
                            return $user->labels()->orderBy('name')->get();
                        }
                    );
                    $view->with('allUserLabels', $allUserLabels);

                    // Statistik pengguna (bisa di-cache jika perhitungannya berat)
                    $view->with('notesCount', Cache::remember('user_notes_count_' . $user->id, now()->addMinutes(5), function () use ($user) {
                        return $user->notes()->where('is_archived', false)->whereNull('deleted_at')->count();
                    }));
                    $view->with('labelsCount', Cache::remember('user_labels_count_' . $user->id, now()->addMinutes(5), function () use ($user) {
                        return $user->labels()->count();
                    }));

                    // Ambil preferensi tema pengguna dari database
                    $view->with('currentTheme', $user->theme_preference ?? config('app.default_theme', 'system'));
                }
            }
        });

        /**
         * Contoh View Composer untuk halaman autentikasi (jika diperlukan).
         * Halaman seperti login atau register mungkin memerlukan data global yang berbeda
         * atau tidak memerlukan data pengguna yang sudah login.
         */
        // View::composer(['layouts.auth', 'auth.*'], function ($view) {
        //     if (!$view->offsetExists('pageTitle')) {
        //         $view->with('pageTitle', config('app.name', 'MyNotes'));
        //     }
        //     // Variabel lain yang spesifik untuk halaman auth bisa ditambahkan di sini
        // });
    }
}