<?php

namespace App\Services;

use App\Models\Kindergarten;
use App\Models\RegistrationDeadline;
use App\Models\ScraperConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class DeadlineScraperService
{
    /**
     * Scrape deadlines for all active configurations.
     */
    public function scrapeAll(int $hoursThreshold = 24): array
    {
        $configs = ScraperConfig::needsScraping($hoursThreshold)
            ->with('kindergarten')
            ->get();

        $results = [
            'total' => $configs->count(),
            'success' => 0,
            'failed' => 0,
            'new_deadlines' => 0,
            'errors' => [],
        ];

        foreach ($configs as $config) {
            try {
                $newDeadlines = $this->scrapeForConfig($config);
                $results['new_deadlines'] += $newDeadlines;
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'kindergarten' => $config->kindergarten->name_en,
                    'error' => $e->getMessage(),
                ];
                
                $config->markAsFailed($e->getMessage());
                Log::error("Scraper failed for kindergarten {$config->kindergarten_id}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Scrape deadlines for a specific kindergarten.
     */
    public function scrapeForKindergarten(Kindergarten $kindergarten): array
    {
        $configs = $kindergarten->scraperConfig()->active()->get();
        
        $results = [
            'success' => false,
            'new_deadlines' => 0,
            'errors' => [],
        ];

        foreach ($configs as $config) {
            try {
                $newDeadlines = $this->scrapeForConfig($config);
                $results['new_deadlines'] += $newDeadlines;
                $results['success'] = true;
            } catch (\Exception $e) {
                $results['errors'][] = $e->getMessage();
                $config->markAsFailed($e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Scrape deadlines using a specific configuration.
     */
    protected function scrapeForConfig(ScraperConfig $config): int
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; HKKindergartenBot/1.0)',
                'Accept' => 'text/html,application/xhtml+xml',
                'Accept-Language' => 'zh-TW,zh;q=0.9,en;q=0.8',
            ])
            ->get($config->target_url);

        if (!$response->successful()) {
            throw new \Exception("HTTP request failed with status {$response->status()}");
        }

        $html = $response->body();
        $deadlines = $this->parseDeadlines($html, $config);
        
        $newCount = 0;
        foreach ($deadlines as $deadline) {
            $created = $this->saveDeadline($config->kindergarten_id, $deadline, $config->target_url);
            if ($created) {
                $newCount++;
            }
        }

        $config->markAsScraped();

        return $newCount;
    }

    /**
     * Parse deadlines from HTML content.
     */
    protected function parseDeadlines(string $html, ScraperConfig $config): array
    {
        $deadlines = [];

        // Suppress DOM warnings for malformed HTML
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($dom);

        // Common patterns to look for deadline information
        $patterns = [
            // Look for text containing common deadline keywords in Chinese
            '報名' => 'application_deadline',
            '申請' => 'application_deadline',
            '截止' => 'application_deadline',
            '面試' => 'interview',
            '開放日' => 'open_day',
            '簡介會' => 'briefing_session',
            '註冊' => 'registration',
        ];

        // If a custom selector is provided, use it
        if (!empty($config->deadline_selector)) {
            $elements = $xpath->query($config->deadline_selector);
            if ($elements && $elements->length > 0) {
                foreach ($elements as $element) {
                    $text = trim($element->textContent);
                    $dateInfo = $this->extractDateFromText($text, $config->date_format);
                    if ($dateInfo) {
                        $deadlines[] = $dateInfo;
                    }
                }
            }
        }

        // Generic search for deadline-related content
        foreach ($patterns as $keyword => $eventType) {
            $nodes = $xpath->query("//*[contains(text(), '{$keyword}')]");
            if ($nodes && $nodes->length > 0) {
                foreach ($nodes as $node) {
                    // Get surrounding text (parent element)
                    $parent = $node->parentNode;
                    $text = $parent ? trim($parent->textContent) : trim($node->textContent);
                    
                    $dateInfo = $this->extractDateFromText($text, $config->date_format);
                    if ($dateInfo) {
                        $dateInfo['event_type'] = $eventType;
                        $deadlines[] = $dateInfo;
                    }
                }
            }
        }

        libxml_clear_errors();

        // Remove duplicates
        return array_unique($deadlines, SORT_REGULAR);
    }

    /**
     * Extract date information from text.
     */
    protected function extractDateFromText(string $text, string $format = 'Y-m-d'): ?array
    {
        // Common date patterns
        $patterns = [
            // 2024年3月1日 or 2024年03月01日
            '/(\d{4})年(\d{1,2})月(\d{1,2})日/u',
            // 2024-03-01 or 2024/03/01
            '/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/',
            // 01/03/2024 (DD/MM/YYYY)
            '/(\d{1,2})\/(\d{1,2})\/(\d{4})/',
            // March 1, 2024
            '/(January|February|March|April|May|June|July|August|September|October|November|December)\s+(\d{1,2}),?\s+(\d{4})/i',
        ];

        foreach ($patterns as $index => $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                try {
                    switch ($index) {
                        case 0: // Chinese format
                        case 1: // ISO format
                            $year = (int) $matches[1];
                            $month = (int) $matches[2];
                            $day = (int) $matches[3];
                            break;
                        case 2: // DD/MM/YYYY
                            $day = (int) $matches[1];
                            $month = (int) $matches[2];
                            $year = (int) $matches[3];
                            break;
                        case 3: // English month name
                            $monthNames = [
                                'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
                                'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
                                'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
                            ];
                            $month = $monthNames[strtolower($matches[1])];
                            $day = (int) $matches[2];
                            $year = (int) $matches[3];
                            break;
                    }

                    // Validate and create date
                    if (checkdate($month, $day, $year)) {
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        
                        // Determine academic year
                        $academicYear = $month >= 9 
                            ? "{$year}-" . ($year + 1) 
                            : ($year - 1) . "-{$year}";

                        return [
                            'deadline_date' => $date,
                            'academic_year' => $academicYear,
                            'event_type' => 'application_deadline', // Default
                            'notes_en' => trim($text),
                        ];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return null;
    }

    /**
     * Save or update a deadline.
     */
    protected function saveDeadline(int $kindergartenId, array $data, string $sourceUrl): bool
    {
        // Check if this deadline already exists
        $existing = RegistrationDeadline::where('kindergarten_id', $kindergartenId)
            ->where('academic_year', $data['academic_year'])
            ->where('event_type', $data['event_type'])
            ->where('deadline_date', $data['deadline_date'])
            ->first();

        if ($existing) {
            return false;
        }

        RegistrationDeadline::create([
            'kindergarten_id' => $kindergartenId,
            'academic_year' => $data['academic_year'],
            'event_type' => $data['event_type'],
            'deadline_date' => $data['deadline_date'],
            'notes_en' => $data['notes_en'] ?? null,
            'notes_zh_tw' => $data['notes_zh_tw'] ?? null,
            'notes_zh_cn' => $data['notes_zh_cn'] ?? null,
            'source_url' => $sourceUrl,
            'is_scraped' => true,
            'is_verified' => false,
        ]);

        return true;
    }
}
