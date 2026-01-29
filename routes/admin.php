<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KindergartenController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\SuggestionController;
use App\Http\Controllers\Admin\DeadlineController;
use App\Http\Controllers\Admin\ScraperController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Kindergarten Management
Route::resource('kindergartens', KindergartenController::class);
Route::post('kindergartens/{kindergarten}/features', [KindergartenController::class, 'addFeature'])->name('kindergartens.features.store');
Route::delete('kindergartens/{kindergarten}/features/{feature}', [KindergartenController::class, 'removeFeature'])->name('kindergartens.features.destroy');

// District Management
Route::resource('districts', DistrictController::class);

// Suggestions Management
Route::get('suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
Route::get('suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');
Route::put('suggestions/{suggestion}/status', [SuggestionController::class, 'updateStatus'])->name('suggestions.status');
Route::put('suggestions/{suggestion}/notes', [SuggestionController::class, 'updateNotes'])->name('suggestions.notes');
Route::get('suggestions/export/csv', [SuggestionController::class, 'exportCsv'])->name('suggestions.export');

// Deadline Management
Route::resource('deadlines', DeadlineController::class);
Route::put('deadlines/{deadline}/verify', [DeadlineController::class, 'verify'])->name('deadlines.verify');

// Scraper Management
Route::get('scraper', [ScraperController::class, 'index'])->name('scraper.index');
Route::post('scraper/run', [ScraperController::class, 'run'])->name('scraper.run');
Route::post('scraper/run/{kindergarten}', [ScraperController::class, 'runSingle'])->name('scraper.run.single');
Route::resource('scraper/configs', ScraperController::class)->names('scraper.configs');

// Import/Export
Route::get('import', [ImportController::class, 'index'])->name('import.index');
Route::post('import/kindergartens', [ImportController::class, 'importKindergartens'])->name('import.kindergartens');
Route::get('export/kindergartens', [ImportController::class, 'exportKindergartens'])->name('export.kindergartens');
Route::get('import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');

// User Management
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::put('users/{user}/admin', [UserController::class, 'toggleAdmin'])->name('users.admin');
