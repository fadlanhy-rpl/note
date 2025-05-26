<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Pastikan Auth facade di-import

// Import semua controller yang digunakan
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda mendaftarkan rute web untuk aplikasi Anda. Rute-rute ini
| dimuat oleh RouteServiceProvider dalam grup yang berisi middleware "web".
|
*/

// --- LANDING PAGE ROUTE ---
Route::get('/', function () {
    if (Auth::check()) {
        // Jika pengguna sudah login, arahkan ke dashboard utama (misalnya, 'home')
        return redirect()->route('home');
    }
    // Jika pengguna belum login, tampilkan landing page
    // Asumsikan $pageTitle tidak terlalu krusial untuk landing page publik,
    // atau Anda bisa set default di view welcome.blade.php itu sendiri.
    return view('welcome');
})->name('landing');


// --- AUTHENTICATION ROUTES (Manual) ---
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// --- APPLICATION ROUTES (Memerlukan Autentikasi) ---
Route::middleware(['auth'])->group(function () {

    // Halaman utama setelah login (Dashboard/Notes Index)
    Route::get('/home', [NoteController::class, 'index'])->name('home');

    // API Endpoint untuk mengambil partial HTML daftar label sidebar (digunakan oleh JavaScript)
    Route::get('/api/sidebar-labels', [LabelController::class, 'getSidebarLabelsPartial'])->name('api.sidebar.labels');

    // NOTES
    Route::get('/notes/search', [NoteController::class, 'search'])->name('notes.search');
    Route::post('/notes/{note}/archive-toggle', [NoteController::class, 'archiveToggle'])->name('notes.archive-toggle');
    Route::resource('notes', NoteController::class)->except(['index']); // 'index' dihandle oleh 'home'

    // LABELS
    // Rute untuk menampilkan notes berdasarkan label tertentu
    Route::get('/labels/{label}', [LabelController::class, 'show'])->name('labels.show');
    // API Endpoints untuk manajemen label via AJAX dari modal (tombol "Edit labels" di sidebar hanya membuka modal)
    Route::post('/labels', [LabelController::class, 'store'])->name('labels.store');
    Route::put('/labels/{label}', [LabelController::class, 'update'])->name('labels.update');
    Route::delete('/labels/{label}', [LabelController::class, 'destroy'])->name('labels.destroy');
    // Jika Anda memiliki halaman "Manage Labels" terpisah yang dirender server:
    // Route::get('/labels/manage', [LabelController::class, 'index'])->name('labels.manage');


    // REMINDERS (Dasar)
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/create', [ReminderController::class, 'create'])->name('reminders.create');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::get('/reminders/{reminder}/edit', [ReminderController::class, 'edit'])->name('reminders.edit');
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');
    Route::post('/reminders/{reminder}/complete', [ReminderController::class, 'complete'])->name('reminders.complete');
    Route::post('/reminders/{reminder}/uncomplete', [ReminderController::class, 'uncomplete'])->name('reminders.uncomplete');
    // Route::post('/notes/{note}/reminders', [ReminderController::class, 'storeFromNote'])->name('notes.reminders.store'); // Jika ada method khusus

    // ARCHIVE
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
    // Route untuk unarchive ada di NoteController->archiveToggle

    // TRASH
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/restore/{noteId}', [TrashController::class, 'restore'])->name('trash.restore'); // Menggunakan ID karena note mungkin soft deleted
    Route::delete('/trash/permanent-delete/{noteId}', [TrashController::class, 'permanentDelete'])->name('trash.permanent-delete');
    Route::delete('/trash/empty', [TrashController::class, 'emptyTrash'])->name('trash.empty');

    // PROFILE & SETTINGS
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/theme', [SettingsController::class, 'updateThemePreference'])->name('settings.theme.update');

    // HELP (Stub)
    Route::get('/help', function () {
        $pageTitle = "Help & Feedback";
        return view('help.index', compact('pageTitle'));
    })->name('help');
});