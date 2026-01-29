<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Kindergarten extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_zh_tw',
        'name_zh_cn',
        'name_en',
        'district_id',
        'address_zh_tw',
        'address_zh_cn',
        'address_en',
        'website_url',
        'has_pn_class',
        'has_k1',
        'has_k2',
        'has_k3',
        'primary_success_rate',
        'ranking_score',
        'description_zh_tw',
        'description_zh_cn',
        'description_en',
        'phone',
        'email',
        'principal_name',
        'established_year',
        'school_type',
        'monthly_fee_min',
        'monthly_fee_max',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_pn_class' => 'boolean',
        'has_k1' => 'boolean',
        'has_k2' => 'boolean',
        'has_k3' => 'boolean',
        'primary_success_rate' => 'decimal:2',
        'ranking_score' => 'integer',
        'monthly_fee_min' => 'decimal:2',
        'monthly_fee_max' => 'decimal:2',
        'is_active' => 'boolean',
        'established_year' => 'integer',
    ];

    /**
     * School type constants
     */
    const TYPE_PRIVATE = 'private';
    const TYPE_NON_PROFIT = 'non_profit';
    const TYPE_GOVERNMENT = 'government';

    /**
     * Get the district.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get school features.
     */
    public function features(): HasMany
    {
        return $this->hasMany(SchoolFeature::class);
    }

    /**
     * Get registration deadlines.
     */
    public function deadlines(): HasMany
    {
        return $this->hasMany(RegistrationDeadline::class);
    }

    /**
     * Get suggestions for this kindergarten.
     */
    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    /**
     * Get users who favorited this kindergarten.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_schools')
            ->withPivot('notes')
            ->withTimestamps();
    }

    /**
     * Get scraper config.
     */
    public function scraperConfig(): HasMany
    {
        return $this->hasMany(ScraperConfig::class);
    }

    /**
     * Get localized name based on current locale.
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'zh-TW' => $this->name_zh_tw,
            'zh-CN' => $this->name_zh_cn,
            default => $this->name_en,
        };
    }

    /**
     * Get localized address.
     */
    public function getLocalizedAddressAttribute(): string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'zh-TW' => $this->address_zh_tw,
            'zh-CN' => $this->address_zh_cn,
            default => $this->address_en,
        };
    }

    /**
     * Get localized description.
     */
    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'zh-TW' => $this->description_zh_tw,
            'zh-CN' => $this->description_zh_cn,
            default => $this->description_en,
        };
    }

    /**
     * Get available class levels as array.
     */
    public function getAvailableClassesAttribute(): array
    {
        $classes = [];
        if ($this->has_pn_class) $classes[] = 'PN';
        if ($this->has_k1) $classes[] = 'K1';
        if ($this->has_k2) $classes[] = 'K2';
        if ($this->has_k3) $classes[] = 'K3';
        return $classes;
    }

    /**
     * Get formatted fee range.
     */
    public function getFeeRangeAttribute(): ?string
    {
        if (!$this->monthly_fee_min && !$this->monthly_fee_max) {
            return null;
        }

        if ($this->monthly_fee_min == $this->monthly_fee_max) {
            return 'HK$' . number_format($this->monthly_fee_min);
        }

        return 'HK$' . number_format($this->monthly_fee_min) . ' - HK$' . number_format($this->monthly_fee_max);
    }

    /**
     * Get upcoming deadlines.
     */
    public function getUpcomingDeadlinesAttribute()
    {
        return $this->deadlines()
            ->where('deadline_date', '>=', now())
            ->orderBy('deadline_date')
            ->get();
    }

    /**
     * Scope for active kindergartens.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for filtering by district.
     */
    public function scopeInDistrict(Builder $query, int $districtId): Builder
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope for filtering by class type.
     */
    public function scopeWithClassType(Builder $query, string $classType): Builder
    {
        return match($classType) {
            'pn' => $query->where('has_pn_class', true),
            'k1' => $query->where('has_k1', true),
            'k2' => $query->where('has_k2', true),
            'k3' => $query->where('has_k3', true),
            default => $query,
        };
    }

    /**
     * Scope for searching by name.
     */
    public function scopeSearchByName(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name_zh_tw', 'LIKE', "%{$search}%")
              ->orWhere('name_zh_cn', 'LIKE', "%{$search}%")
              ->orWhere('name_en', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope for ordering by ranking.
     */
    public function scopeOrderByRanking(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('ranking_score', $direction);
    }

    /**
     * Scope for filtering by minimum success rate.
     */
    public function scopeMinSuccessRate(Builder $query, float $rate): Builder
    {
        return $query->where('primary_success_rate', '>=', $rate);
    }
}
