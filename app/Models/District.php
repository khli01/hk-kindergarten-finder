<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
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
        'region',
    ];

    /**
     * Region constants
     */
    const REGION_HONG_KONG_ISLAND = 'hong_kong_island';
    const REGION_KOWLOON = 'kowloon';
    const REGION_NEW_TERRITORIES = 'new_territories';

    /**
     * Get kindergartens in this district.
     */
    public function kindergartens(): HasMany
    {
        return $this->hasMany(Kindergarten::class);
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
     * Get localized region name.
     */
    public function getLocalizedRegionAttribute(): string
    {
        $locale = app()->getLocale();
        
        $regions = [
            'hong_kong_island' => [
                'zh-TW' => '香港島',
                'zh-CN' => '香港岛',
                'en' => 'Hong Kong Island',
            ],
            'kowloon' => [
                'zh-TW' => '九龍',
                'zh-CN' => '九龙',
                'en' => 'Kowloon',
            ],
            'new_territories' => [
                'zh-TW' => '新界',
                'zh-CN' => '新界',
                'en' => 'New Territories',
            ],
        ];

        return $regions[$this->region][$locale] ?? $regions[$this->region]['en'];
    }

    /**
     * Scope for filtering by region.
     */
    public function scopeByRegion($query, string $region)
    {
        return $query->where('region', $region);
    }
}
