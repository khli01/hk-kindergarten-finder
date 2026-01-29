<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kindergarten;
use App\Models\RegistrationDeadline;
use Illuminate\Http\Request;

class DeadlineController extends Controller
{
    /**
     * Display a listing of deadlines.
     */
    public function index(Request $request)
    {
        $query = RegistrationDeadline::with('kindergarten');

        if ($request->filled('kindergarten')) {
            $query->where('kindergarten_id', $request->kindergarten);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified);
        }

        if ($request->filled('upcoming') && $request->upcoming) {
            $query->upcoming();
        }

        $deadlines = $query->orderBy('deadline_date', 'desc')->paginate(20);
        $kindergartens = Kindergarten::orderBy('name_en')->get();

        return view('admin.deadlines.index', compact('deadlines', 'kindergartens'));
    }

    /**
     * Show form to create a new deadline.
     */
    public function create()
    {
        $kindergartens = Kindergarten::active()->orderBy('name_en')->get();
        return view('admin.deadlines.create', compact('kindergartens'));
    }

    /**
     * Store a new deadline.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string|max:20',
            'event_type' => 'required|in:application_start,application_deadline,interview,result_announcement,registration,open_day,briefing_session,other',
            'deadline_date' => 'required|date',
            'deadline_time' => 'nullable|date_format:H:i',
            'notes_zh_tw' => 'nullable|string',
            'notes_zh_cn' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'source_url' => 'nullable|url',
            'is_verified' => 'boolean',
        ]);

        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_scraped'] = false;

        RegistrationDeadline::create($validated);

        return redirect()->route('admin.deadlines.index')
            ->with('success', 'Deadline created successfully.');
    }

    /**
     * Show form to edit a deadline.
     */
    public function edit(RegistrationDeadline $deadline)
    {
        $kindergartens = Kindergarten::active()->orderBy('name_en')->get();
        return view('admin.deadlines.edit', compact('deadline', 'kindergartens'));
    }

    /**
     * Update a deadline.
     */
    public function update(Request $request, RegistrationDeadline $deadline)
    {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string|max:20',
            'event_type' => 'required|in:application_start,application_deadline,interview,result_announcement,registration,open_day,briefing_session,other',
            'deadline_date' => 'required|date',
            'deadline_time' => 'nullable|date_format:H:i',
            'notes_zh_tw' => 'nullable|string',
            'notes_zh_cn' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'source_url' => 'nullable|url',
            'is_verified' => 'boolean',
        ]);

        $validated['is_verified'] = $request->boolean('is_verified');

        $deadline->update($validated);

        return redirect()->route('admin.deadlines.index')
            ->with('success', 'Deadline updated successfully.');
    }

    /**
     * Delete a deadline.
     */
    public function destroy(RegistrationDeadline $deadline)
    {
        $deadline->delete();
        return back()->with('success', 'Deadline deleted.');
    }

    /**
     * Toggle verification status.
     */
    public function verify(RegistrationDeadline $deadline)
    {
        $deadline->update(['is_verified' => !$deadline->is_verified]);
        return back()->with('success', 'Verification status updated.');
    }
}
