<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /** Profile page */
    public function profile()
    {
        return view('pages.admin.settings-profile');
    }

    /** Update name & email */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        return back()->with('status', 'profile-updated');
    }

    /** Security page */
    public function security()
    {
        return view('pages.admin.settings-security');
    }

    /** Update password */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /** Appearance page */
    public function appearance()
    {
        return view('pages.admin.settings-appearance');
    }

    /** Update profile photo */
    public function updateProfilePhoto(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'foto_profil' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Delete old photo if exists
        if ($user->foto_profil && Storage::exists($user->foto_profil)) {
            Storage::delete($user->foto_profil);
        }

        // Store new photo
        $path = $validated['foto_profil']->store('profile-photos', 'public');

        $user->update(['foto_profil' => $path]);

        // Refresh user model to get updated data
        $user->refresh();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui']);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui');
    }

    /** Delete profile photo */
    public function deleteProfilePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->foto_profil && Storage::exists($user->foto_profil)) {
            Storage::delete($user->foto_profil);
        }

        $user->update(['foto_profil' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus');
    }
}
