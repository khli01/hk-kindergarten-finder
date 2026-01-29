<?php

namespace App\Http\Controllers;

use App\Models\Kindergarten;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display user's favorites.
     */
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['kindergarten.district', 'kindergarten.features'])
            ->paginate(12);

        return view('user.favorites', compact('favorites'));
    }

    /**
     * Add a kindergarten to favorites.
     */
    public function store(Kindergarten $kindergarten)
    {
        $user = auth()->user();

        // Check if already favorited
        if ($user->hasFavorited($kindergarten)) {
            return back()->with('info', 'Already in favorites');
        }

        $user->favorites()->create([
            'kindergarten_id' => $kindergarten->id,
        ]);

        return back()->with('success', __('messages.add_to_favorites'));
    }

    /**
     * Remove a kindergarten from favorites.
     */
    public function destroy(Kindergarten $kindergarten)
    {
        auth()->user()
            ->favorites()
            ->where('kindergarten_id', $kindergarten->id)
            ->delete();

        return back()->with('success', __('messages.remove_from_favorites'));
    }

    /**
     * Update notes for a favorite.
     */
    public function updateNotes(Request $request, Kindergarten $kindergarten)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        auth()->user()
            ->favorites()
            ->where('kindergarten_id', $kindergarten->id)
            ->update(['notes' => $request->notes]);

        return back()->with('success', __('messages.save'));
    }
}
