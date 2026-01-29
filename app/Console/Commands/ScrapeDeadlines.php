<?php

namespace App\Console\Commands;

use App\Services\DeadlineScraperService;
use Illuminate\Console\Command;

class ScrapeDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:deadlines 
                            {--hours=24 : Only scrape configs that haven\'t been scraped in X hours}
                            {--kindergarten= : Scrape only a specific kindergarten ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape registration deadlines from kindergarten websites';

    /**
     * Execute the console command.
     */
    public function handle(DeadlineScraperService $scraper)
    {
        $this->info('Starting deadline scraper...');

        if ($kindergartenId = $this->option('kindergarten')) {
            $kindergarten = \App\Models\Kindergarten::find($kindergartenId);
            
            if (!$kindergarten) {
                $this->error("Kindergarten with ID {$kindergartenId} not found.");
                return 1;
            }

            $this->info("Scraping deadlines for: {$kindergarten->name_en}");
            $results = $scraper->scrapeForKindergarten($kindergarten);

            if ($results['success']) {
                $this->info("Successfully scraped. New deadlines found: {$results['new_deadlines']}");
            } else {
                $this->error("Scraping failed.");
                foreach ($results['errors'] as $error) {
                    $this->error("  - {$error}");
                }
            }

            return $results['success'] ? 0 : 1;
        }

        $hours = (int) $this->option('hours');
        $this->info("Scraping configurations not updated in {$hours} hours...");

        $results = $scraper->scrapeAll($hours);

        $this->info("Scraping completed:");
        $this->info("  Total configs: {$results['total']}");
        $this->info("  Successful: {$results['success']}");
        $this->info("  Failed: {$results['failed']}");
        $this->info("  New deadlines: {$results['new_deadlines']}");

        if (count($results['errors']) > 0) {
            $this->warn("Errors:");
            foreach ($results['errors'] as $error) {
                $this->warn("  - {$error['kindergarten']}: {$error['error']}");
            }
        }

        return $results['failed'] > 0 ? 1 : 0;
    }
}
