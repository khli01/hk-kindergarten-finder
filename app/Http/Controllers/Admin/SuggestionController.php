<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SuggestionController extends Controller
{
    /**
     * Display a listing of suggestions.
     */
    public function index(Request $request)
    {
        $query = Suggestion::with(['user', 'kindergarten']);

        if ($request->filled('status')) {
            $query->ofStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        if ($request->filled('search')) {
            $query->where('content', 'LIKE', '%' . $request->search . '%');
        }

        $suggestions = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => Suggestion::count(),
            'pending' => Suggestion::pending()->count(),
            'reviewed' => Suggestion::ofStatus('reviewed')->count(),
            'processed' => Suggestion::ofStatus('processed')->count(),
        ];

        return view('admin.suggestions.index', compact('suggestions', 'stats'));
    }

    /**
     * Display the specified suggestion.
     */
    public function show(Suggestion $suggestion)
    {
        $suggestion->load(['user', 'kindergarten']);
        return view('admin.suggestions.show', compact('suggestion'));
    }

    /**
     * Update the status of a suggestion.
     */
    public function updateStatus(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,processed,archived',
        ]);

        $suggestion->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Update admin notes for a suggestion.
     */
    public function updateNotes(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $suggestion->update($validated);

        return back()->with('success', 'Notes saved successfully.');
    }

    /**
     * Export suggestions to CSV for AI training.
     */
    public function exportCsv(Request $request)
    {
        $query = Suggestion::with(['user', 'kindergarten']);

        if ($request->filled('status')) {
            $query->ofStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        $suggestions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="suggestions_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($suggestions) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'ID',
                'User Email',
                'Kindergarten',
                'Category',
                'Content',
                'Status',
                'Admin Notes',
                'Created At',
            ]);

            foreach ($suggestions as $suggestion) {
                fputcsv($file, [
                    $suggestion->id,
                    $suggestion->user->email,
                    $suggestion->kindergarten?->name_en ?? 'General',
                    $suggestion->category,
                    $suggestion->content,
                    $suggestion->status,
                    $suggestion->admin_notes,
                    $suggestion->created_at->toDateTimeString(),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
