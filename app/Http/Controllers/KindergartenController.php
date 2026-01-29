<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Kindergarten;
use App\Models\RegistrationDeadline;
use Illuminate\Http\Request;

class KindergartenController extends Controller
{
    /**
     * Display a listing of kindergartens with filters.
     */
    public function index(Request $request)
    {
        $query = Kindergarten::active()->with(['district', 'features']);

        // Search by name
        if ($request->filled('search')) {
            $query->searchByName($request->search);
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->inDistrict($request->district);
        }

        // Filter by class type
        if ($request->filled('class_type')) {
            $query->withClassType($request->class_type);
        }

        // Filter by minimum ranking
        if ($request->filled('min_ranking')) {
            $query->where('ranking_score', '>=', $request->min_ranking);
        }

        // Filter by minimum success rate
        if ($request->filled('min_success_rate')) {
            $query->minSuccessRate($request->min_success_rate);
        }

        // Filter by PN class availability
        if ($request->boolean('has_pn')) {
            $query->where('has_pn_class', true);
        }

        // Filter by school type
        if ($request->filled('school_type')) {
            $query->where('school_type', $request->school_type);
        }

        // Sorting
        $sortBy = $request->get('sort', 'ranking');
        $sortDir = $request->get('dir', 'desc');

        switch ($sortBy) {
            case 'name':
                $locale = app()->getLocale();
                $nameColumn = match($locale) {
                    'zh-TW' => 'name_zh_tw',
                    'zh-CN' => 'name_zh_cn',
                    default => 'name_en',
                };
                $query->orderBy($nameColumn, $sortDir);
                break;
            case 'success_rate':
                $query->orderBy('primary_success_rate', $sortDir);
                break;
            case 'ranking':
            default:
                $query->orderByRanking($sortDir);
                break;
        }

        $kindergartens = $query->paginate(12)->withQueryString();
        $districts = District::all();

        return view('kindergartens.index', compact('kindergartens', 'districts'));
    }

    /**
     * Display the specified kindergarten.
     */
    public function show(Kindergarten $kindergarten)
    {
        $kindergarten->load([
            'district',
            'features',
            'deadlines' => function ($query) {
                $query->upcoming()->take(5);
            }
        ]);

        // Get related kindergartens in same district
        $relatedSchools = Kindergarten::active()
            ->where('id', '!=', $kindergarten->id)
            ->inDistrict($kindergarten->district_id)
            ->orderByRanking()
            ->take(4)
            ->get();

        // Check if user has favorited
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->hasFavorited($kindergarten);
        }

        return view('kindergartens.show', compact('kindergarten', 'relatedSchools', 'isFavorited'));
    }

    /**
     * Display kindergartens by district.
     */
    public function byDistrict(District $district)
    {
        $kindergartens = Kindergarten::active()
            ->with('features')
            ->inDistrict($district->id)
            ->orderByRanking()
            ->paginate(12);

        $districts = District::all();

        return view('kindergartens.index', [
            'kindergartens' => $kindergartens,
            'districts' => $districts,
            'selectedDistrict' => $district,
        ]);
    }

    /**
     * Display all upcoming deadlines.
     */
    public function deadlines(Request $request)
    {
        $query = RegistrationDeadline::upcoming()
            ->with('kindergarten.district');

        // Filter by district
        if ($request->filled('district')) {
            $query->whereHas('kindergarten', function ($q) use ($request) {
                $q->where('district_id', $request->district);
            });
        }

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->forYear($request->academic_year);
        }

        $deadlines = $query->paginate(20)->withQueryString();
        $districts = District::all();

        // Get unique academic years for filter
        $academicYears = RegistrationDeadline::distinct()
            ->pluck('academic_year')
            ->sort()
            ->reverse();

        return view('deadlines.index', compact('deadlines', 'districts', 'academicYears'));
    }
}
