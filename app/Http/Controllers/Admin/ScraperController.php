<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ScrapeSchoolWebsite;
use App\Models\Kindergarten;
use App\Models\ScraperConfig;
use App\Services\DeadlineScraperService;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    /**
     * Display the scraper dashboard.
     */
    public function index()
    {
        $configs = ScraperConfig::with('kindergarten')
            ->latest('last_scraped_at')
            ->paginate(20);

        $stats = [
            'total_configs' => ScraperConfig::count(),
            'active_configs' => ScraperConfig::active()->count(),
            'configs_with_errors' => ScraperConfig::whereNotNull('last_error')->count(),
            'needs_scraping' => ScraperConfig::needsScraping()->count(),
        ];

        $kindergartensWithoutConfig = Kindergarten::whereDoesntHave('scraperConfig')
            ->whereNotNull('website_url')
            ->count();

        return view('admin.scraper.index', compact('configs', 'stats', 'kindergartensWithoutConfig'));
    }

    /**
     * Run the scraper for all configurations.
     */
    public function run(Request $request, DeadlineScraperService $scraper)
    {
        $hours = $request->input('hours', 24);
        
        $results = $scraper->scrapeAll($hours);

        $message = "Scraper completed: {$results['success']}/{$results['total']} successful, {$results['new_deadlines']} new deadlines found.";
        
        if ($results['failed'] > 0) {
            $message .= " {$results['failed']} failed.";
            return back()->with('warning', $message);
        }

        return back()->with('success', $message);
    }

    /**
     * Run the scraper for a single kindergarten.
     */
    public function runSingle(Kindergarten $kindergarten, DeadlineScraperService $scraper)
    {
        $results = $scraper->scrapeForKindergarten($kindergarten);

        if ($results['success']) {
            return back()->with('success', "Scraped {$kindergarten->name_en}: {$results['new_deadlines']} new deadlines found.");
        }

        return back()->with('error', "Scraping failed for {$kindergarten->name_en}: " . implode(', ', $results['errors']));
    }

    /**
     * Show form to create a new scraper config.
     */
    public function create()
    {
        $kindergartens = Kindergarten::whereNotNull('website_url')
            ->whereDoesntHave('scraperConfig')
            ->orderBy('name_en')
            ->get();

        return view('admin.scraper.create', compact('kindergartens'));
    }

    /**
     * Store a new scraper config.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id|unique:scraper_configs,kindergarten_id',
            'target_url' => 'required|url',
            'deadline_selector' => 'nullable|string|max:500',
            'date_format' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['date_format'] = $validated['date_format'] ?? 'Y-m-d';

        ScraperConfig::create($validated);

        return redirect()->route('admin.scraper.index')
            ->with('success', 'Scraper configuration created successfully.');
    }

    /**
     * Show form to edit a scraper config.
     */
    public function edit(ScraperConfig $config)
    {
        return view('admin.scraper.edit', ['config' => $config]);
    }

    /**
     * Update a scraper config.
     */
    public function update(Request $request, ScraperConfig $config)
    {
        $validated = $request->validate([
            'target_url' => 'required|url',
            'deadline_selector' => 'nullable|string|max:500',
            'date_format' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $config->update($validated);

        return redirect()->route('admin.scraper.index')
            ->with('success', 'Scraper configuration updated successfully.');
    }

    /**
     * Delete a scraper config.
     */
    public function destroy(ScraperConfig $config)
    {
        $config->delete();

        return back()->with('success', 'Scraper configuration deleted.');
    }
}
