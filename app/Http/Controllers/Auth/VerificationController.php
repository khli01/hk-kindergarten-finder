<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function notice(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Verify the user's email.
     */
    public function verify(Request $request, string $token)
    {
        $email = $request->query('email');

        $user = User::where('email', $email)
            ->where('verification_token', $token)
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', __('auth.invalid_verification_link'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('info', __('auth.already_verified'));
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')
            ->with('success', __('auth.email_verified'));
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', __('auth.already_verified'));
        }

        // Generate new verification token
        $verificationToken = Str::random(64);
        $user->update(['verification_token' => $verificationToken]);

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'token' => $verificationToken,
            'email' => $user->email,
        ]);

        // Resend verification email
        Mail::to($user->email)->send(new VerificationEmail($user, $verificationUrl));

        return back()->with('success', __('auth.verification_link_sent'));
    }
}
