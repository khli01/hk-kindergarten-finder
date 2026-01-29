<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['favorites', 'suggestions']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('verified')) {
            $query->where('email_verified', $request->verified);
        }

        if ($request->filled('admin')) {
            $query->where('is_admin', $request->admin);
        }

        $users = $query->latest()->paginate(20);

        $stats = [
            'total' => User::count(),
            'verified' => User::where('email_verified', true)->count(),
            'admins' => User::where('is_admin', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['favorites.kindergarten', 'suggestions']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle admin status.
     */
    public function toggleAdmin(User $user)
    {
        // Prevent removing own admin status
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot modify your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'granted' : 'revoked';
        return back()->with('success', "Admin status {$status} for {$user->name}.");
    }
}
