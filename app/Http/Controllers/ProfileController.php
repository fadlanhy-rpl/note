<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // <-- PASTIKAN INI ADA
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // <-- PASTIKAN INI ADA
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_image' => ['nullable', 'string'], // Menerima base64 string
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $profileData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ];

        if ($request->filled('profile_image')) {
            if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            $imageData = $request->input('profile_image');
            // Cek jika data adalah base64 valid
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    return back()->withErrors(['profile_image' => 'Invalid image type.'])->withInput();
                }
                $imageData = base64_decode($imageData);
                if ($imageData === false) {
                    return back()->withErrors(['profile_image' => 'Base64 decode failed.'])->withInput();
                }
            } else {
                return back()->withErrors(['profile_image' => 'Invalid base64 image data.'])->withInput();
            }

            $fileName = 'profile_images/' . Str::random(30) . '.' . $type;
            Storage::disk('public')->put($fileName, $imageData);
            $profileData['profile_image_path'] = $fileName;
        }

        $user->update($profileData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The provided password does not match your current password.');
                }
            }],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()], // Aturan password lebih kuat
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Opsional: Logout user dari sesi lain jika password diubah
        // Auth::logoutOtherDevices($request->password);

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully.'); // Redirect kembali ke edit
    }
}