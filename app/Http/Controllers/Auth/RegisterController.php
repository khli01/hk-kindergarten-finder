<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'preferred_language' => ['sometimes', 'in:zh-TW,zh-CN,en'],
        ]);

        // Generate verification token
        $verificationToken = Str::random(64);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'verification_token' => $verificationToken,
            'email_verified' => false,
            'preferred_language' => $validated['preferred_language'] ?? app()->getLocale(),
        ]);

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'token' => $verificationToken,
            'email' => $user->email,
        ]);

        // Send verification email via Mailgun
        Mail::to($user->email)->send(new VerificationEmail($user, $verificationUrl));

        return redirect()->route('verification.notice')
            ->with('success', __('auth.registration_success'));
    }
}
