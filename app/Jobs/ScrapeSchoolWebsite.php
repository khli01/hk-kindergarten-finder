<?php

namespace App\Jobs;

use App\Models\Kindergarten;
use App\Services\DeadlineScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeSchoolWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Kindergarten $kindergarten
    ) {}

    /**
     * Execute the job.
     */
    public function handle(DeadlineScraperService $scraper): void
    {
        Log::info("Scraping deadlines for kindergarten: {$this->kindergarten->name_en}");

        try {
            $results = $scraper->scrapeForKindergarten($this->kindergarten);
            
            if ($results['success']) {
                Log::info("Successfully scraped {$results['new_deadlines']} new deadlines for {$this->kindergarten->name_en}");
            } else {
                Log::warning("Scraping completed with errors for {$this->kindergarten->name_en}", $results['errors']);
            }
        } catch (\Exception $e) {
            Log::error("Scraping failed for {$this->kindergarten->name_en}: " . $e->getMessage());
            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Scraping job permanently failed for kindergarten {$this->kindergarten->id}: " . $exception->getMessage());
    }
}
