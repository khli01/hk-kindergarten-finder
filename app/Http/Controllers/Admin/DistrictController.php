<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of districts.
     */
    public function index()
    {
        $districts = District::withCount('kindergartens')
            ->orderBy('region')
            ->orderBy('name_en')
            ->get()
            ->groupBy('region');

        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Show form to edit a district.
     */
    public function edit(District $district)
    {
        return view('admin.districts.edit', compact('district'));
    }

    /**
     * Update a district.
     */
    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'name_zh_tw' => 'required|string|max:255',
            'name_zh_cn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $district->update($validated);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District updated successfully.');
    }
}
