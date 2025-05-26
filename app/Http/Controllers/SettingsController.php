<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Untuk validasi 'in'

class SettingsController extends Controller
{
    public function index()
    {
        $pageTitle = "Settings";
        $user = Auth::user();
        // Ambil preferensi tema user saat ini dari database
        $currentTheme = $user->theme_preference ?? 'system'; // Default ke system jika null

        return view('settings.index', compact('pageTitle', 'currentTheme'));
    }

    public function updateThemePreference(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'theme' => ['required', 'string', Rule::in(['light', 'dark', 'system'])],
        ]);

        $user->theme_preference = $validated['theme'];
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Theme preference updated.']);
        }

        return back()->with('success', 'Theme preference updated successfully.');
    }

    // Tambahkan method lain untuk update setting lain jika perlu
}