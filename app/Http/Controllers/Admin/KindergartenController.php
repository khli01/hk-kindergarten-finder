<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Kindergarten;
use App\Models\SchoolFeature;
use Illuminate\Http\Request;

class KindergartenController extends Controller
{
    /**
     * Display a listing of kindergartens.
     */
    public function index(Request $request)
    {
        $query = Kindergarten::with('district');

        if ($request->filled('search')) {
            $query->searchByName($request->search);
        }

        if ($request->filled('district')) {
            $query->inDistrict($request->district);
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        $kindergartens = $query->latest()->paginate(20);
        $districts = District::all();

        return view('admin.kindergartens.index', compact('kindergartens', 'districts'));
    }

    /**
     * Show the form for creating a new kindergarten.
     */
    public function create()
    {
        $districts = District::all();
        return view('admin.kindergartens.create', compact('districts'));
    }

    /**
     * Store a newly created kindergarten.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_zh_tw' => 'required|string|max:255',
            'name_zh_cn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'address_zh_tw' => 'required|string|max:500',
            'address_zh_cn' => 'required|string|max:500',
            'address_en' => 'required|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'has_pn_class' => 'boolean',
            'has_k1' => 'boolean',
            'has_k2' => 'boolean',
            'has_k3' => 'boolean',
            'primary_success_rate' => 'nullable|numeric|min:0|max:100',
            'ranking_score' => 'nullable|integer|min:0|max:100',
            'description_zh_tw' => 'nullable|string',
            'description_zh_cn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'school_type' => 'nullable|in:private,non_profit,government',
            'monthly_fee_min' => 'nullable|numeric|min:0',
            'monthly_fee_max' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['has_pn_class'] = $request->boolean('has_pn_class');
        $validated['has_k1'] = $request->boolean('has_k1', true);
        $validated['has_k2'] = $request->boolean('has_k2', true);
        $validated['has_k3'] = $request->boolean('has_k3', true);
        $validated['is_active'] = $request->boolean('is_active', true);

        $kindergarten = Kindergarten::create($validated);

        return redirect()->route('admin.kindergartens.show', $kindergarten)
            ->with('success', 'Kindergarten created successfully.');
    }

    /**
     * Display the specified kindergarten.
     */
    public function show(Kindergarten $kindergarten)
    {
        $kindergarten->load(['district', 'features', 'deadlines', 'suggestions']);
        return view('admin.kindergartens.show', compact('kindergarten'));
    }

    /**
     * Show the form for editing the specified kindergarten.
     */
    public function edit(Kindergarten $kindergarten)
    {
        $districts = District::all();
        return view('admin.kindergartens.edit', compact('kindergarten', 'districts'));
    }

    /**
     * Update the specified kindergarten.
     */
    public function update(Request $request, Kindergarten $kindergarten)
    {
        $validated = $request->validate([
            'name_zh_tw' => 'required|string|max:255',
            'name_zh_cn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'address_zh_tw' => 'required|string|max:500',
            'address_zh_cn' => 'required|string|max:500',
            'address_en' => 'required|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'has_pn_class' => 'boolean',
            'has_k1' => 'boolean',
            'has_k2' => 'boolean',
            'has_k3' => 'boolean',
            'primary_success_rate' => 'nullable|numeric|min:0|max:100',
            'ranking_score' => 'nullable|integer|min:0|max:100',
            'description_zh_tw' => 'nullable|string',
            'description_zh_cn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'school_type' => 'nullable|in:private,non_profit,government',
            'monthly_fee_min' => 'nullable|numeric|min:0',
            'monthly_fee_max' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['has_pn_class'] = $request->boolean('has_pn_class');
        $validated['has_k1'] = $request->boolean('has_k1');
        $validated['has_k2'] = $request->boolean('has_k2');
        $validated['has_k3'] = $request->boolean('has_k3');
        $validated['is_active'] = $request->boolean('is_active');

        $kindergarten->update($validated);

        return redirect()->route('admin.kindergartens.show', $kindergarten)
            ->with('success', 'Kindergarten updated successfully.');
    }

    /**
     * Remove the specified kindergarten.
     */
    public function destroy(Kindergarten $kindergarten)
    {
        $kindergarten->delete();

        return redirect()->route('admin.kindergartens.index')
            ->with('success', 'Kindergarten deleted successfully.');
    }

    /**
     * Add a feature to the kindergarten.
     */
    public function addFeature(Request $request, Kindergarten $kindergarten)
    {
        $validated = $request->validate([
            'feature_type' => 'required|in:teaching_method,language,curriculum,facility,extracurricular,award,strength,other',
            'value_zh_tw' => 'required|string|max:255',
            'value_zh_cn' => 'required|string|max:255',
            'value_en' => 'required|string|max:255',
        ]);

        $kindergarten->features()->create($validated);

        return back()->with('success', 'Feature added successfully.');
    }

    /**
     * Remove a feature from the kindergarten.
     */
    public function removeFeature(Kindergarten $kindergarten, SchoolFeature $feature)
    {
        if ($feature->kindergarten_id !== $kindergarten->id) {
            abort(404);
        }

        $feature->delete();

        return back()->with('success', 'Feature removed successfully.');
    }
}
