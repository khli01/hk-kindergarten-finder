<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Kindergarten;
use App\Models\RegistrationDeadline;
use App\Models\Suggestion;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('email_verified', true)->count(),
            'total_kindergartens' => Kindergarten::count(),
            'active_kindergartens' => Kindergarten::active()->count(),
            'total_districts' => District::count(),
            'total_deadlines' => RegistrationDeadline::count(),
            'upcoming_deadlines' => RegistrationDeadline::upcoming()->count(),
            'total_suggestions' => Suggestion::count(),
            'pending_suggestions' => Suggestion::pending()->count(),
        ];

        // Recent suggestions
        $recentSuggestions = Suggestion::with(['user', 'kindergarten'])
            ->latest()
            ->take(5)
            ->get();

        // Recently added kindergartens
        $recentKindergartens = Kindergarten::with('district')
            ->latest()
            ->take(5)
            ->get();

        // Suggestions by category
        $suggestionsByCategory = Suggestion::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        // Kindergartens by district
        $kindergartensByDistrict = Kindergarten::selectRaw('district_id, count(*) as count')
            ->groupBy('district_id')
            ->with('district')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentSuggestions',
            'recentKindergartens',
            'suggestionsByCategory',
            'kindergartensByDistrict'
        ));
    }
}
