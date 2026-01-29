<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get user's favorites
        $favorites = $user->favorites()
            ->with('kindergarten.district')
            ->latest()
            ->take(5)
            ->get();

        // Get user's suggestions
        $suggestions = $user->suggestions()
            ->with('kindergarten')
            ->latest()
            ->take(5)
            ->get();

        // Statistics
        $stats = [
            'favorites_count' => $user->favorites()->count(),
            'suggestions_count' => $user->suggestions()->count(),
        ];

        return view('user.dashboard', compact('favorites', 'suggestions', 'stats'));
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        return view('user.profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'preferred_language' => ['required', 'in:zh-TW,zh-CN,en'],
        ]);

        // Check if email changed
        $emailChanged = $user->email !== $validated['email'];

        $user->update($validated);

        // If email changed, require re-verification
        if ($emailChanged) {
            $user->update([
                'email_verified' => false,
                'email_verified_at' => null,
            ]);
            
            // TODO: Send new verification email
            return redirect()->route('verification.notice')
                ->with('info', __('auth.verify_email_first'));
        }

        // Update session locale
        session(['locale' => $validated['preferred_language']]);

        return back()->with('success', __('messages.profile_updated'));
    }

    /**
     * Update the user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', __('messages.password_updated'));
    }
}
