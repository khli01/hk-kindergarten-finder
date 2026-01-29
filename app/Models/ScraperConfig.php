<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ScraperConfig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kindergarten_id',
        'target_url',
        'deadline_selector',
        'date_format',
        'is_active',
        'last_scraped_at',
        'last_error',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_scraped_at' => 'datetime',
    ];

    /**
     * Get the kindergarten.
     */
    public function kindergarten(): BelongsTo
    {
        return $this->belongsTo(Kindergarten::class);
    }

    /**
     * Check if scraper has error.
     */
    public function hasError(): bool
    {
        return !empty($this->last_error);
    }

    /**
     * Mark as scraped successfully.
     */
    public function markAsScraped(): void
    {
        $this->update([
            'last_scraped_at' => now(),
            'last_error' => null,
        ]);
    }

    /**
     * Mark as failed with error.
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'last_scraped_at' => now(),
            'last_error' => $error,
        ]);
    }

    /**
     * Scope for active configs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for configs that need scraping.
     */
    public function scopeNeedsScraping(Builder $query, int $hours = 24): Builder
    {
        return $query->active()
                     ->where(function ($q) use ($hours) {
                         $q->whereNull('last_scraped_at')
                           ->orWhere('last_scraped_at', '<', now()->subHours($hours));
                     });
    }
}
