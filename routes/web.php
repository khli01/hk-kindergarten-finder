<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KindergartenController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Kindergarten public routes
Route::get('/kindergartens', [KindergartenController::class, 'index'])->name('kindergartens.index');
Route::get('/kindergartens/{kindergarten}', [KindergartenController::class, 'show'])->name('kindergartens.show');
Route::get('/kindergartens/district/{district}', [KindergartenController::class, 'byDistrict'])->name('kindergartens.district');

// Deadlines public page
Route::get('/deadlines', [KindergartenController::class, 'deadlines'])->name('deadlines.index');

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Email Verification Routes
Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Routes requiring email verification
    Route::middleware('verified')->group(function () {
        // User Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

        // Favorites
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/{kindergarten}', [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{kindergarten}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
        Route::put('/favorites/{kindergarten}/notes', [FavoriteController::class, 'updateNotes'])->name('favorites.notes');

        // Suggestions (private feedback for AI)
        Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
        Route::get('/suggestions/create', [SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
        Route::get('/suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    require __DIR__.'/admin.php';
});
