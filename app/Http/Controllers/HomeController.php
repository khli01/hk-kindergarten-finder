<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Kindergarten;
use App\Models\RegistrationDeadline;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     */
    public function index()
    {
        // Get featured/top-ranked kindergartens
        $featuredKindergartens = Kindergarten::active()
            ->with('district')
            ->orderByRanking()
            ->take(6)
            ->get();

        // Get upcoming deadlines
        $upcomingDeadlines = RegistrationDeadline::upcoming()
            ->with('kindergarten')
            ->verified()
            ->take(5)
            ->get();

        // Get districts for quick search
        $districts = District::withCount(['kindergartens' => function ($query) {
            $query->active();
        }])->get();

        // Statistics
        $stats = [
            'total_schools' => Kindergarten::active()->count(),
            'total_districts' => District::count(),
            'upcoming_deadlines' => RegistrationDeadline::upcoming()->count(),
        ];

        return view('home', compact(
            'featuredKindergartens',
            'upcomingDeadlines',
            'districts',
            'stats'
        ));
    }

    /**
     * Show the about page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
