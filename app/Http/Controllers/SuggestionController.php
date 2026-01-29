<?php

namespace App\Http\Controllers;

use App\Models\Kindergarten;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    /**
     * Display user's suggestions.
     */
    public function index()
    {
        $suggestions = auth()->user()
            ->suggestions()
            ->with('kindergarten')
            ->latest()
            ->paginate(10);

        return view('user.suggestions.index', compact('suggestions'));
    }

    /**
     * Show the form for creating a new suggestion.
     */
    public function create(Request $request)
    {
        $kindergarten = null;
        if ($request->has('kindergarten')) {
            $kindergarten = Kindergarten::find($request->kindergarten);
        }

        $kindergartens = Kindergarten::active()
            ->orderBy('name_' . (app()->getLocale() === 'en' ? 'en' : 'zh_tw'))
            ->get();

        return view('user.suggestions.create', compact('kindergarten', 'kindergartens'));
    }

    /**
     * Store a new suggestion.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kindergarten_id' => ['nullable', 'exists:kindergartens,id'],
            'category' => ['required', 'in:school_info,ranking_feedback,feature_request,data_correction,general,other'],
            'content' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        auth()->user()->suggestions()->create([
            'kindergarten_id' => $validated['kindergarten_id'],
            'category' => $validated['category'],
            'content' => $validated['content'],
            'status' => 'pending',
        ]);

        return redirect()->route('suggestions.index')
            ->with('success', __('messages.suggestion_submitted'));
    }

    /**
     * Display the specified suggestion.
     */
    public function show(Suggestion $suggestion)
    {
        // Ensure user owns this suggestion
        if ($suggestion->user_id !== auth()->id()) {
            abort(403);
        }

        $suggestion->load('kindergarten');

        return view('user.suggestions.show', compact('suggestion'));
    }
}
