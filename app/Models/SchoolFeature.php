<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolFeature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kindergarten_id',
        'feature_type',
        'value_zh_tw',
        'value_zh_cn',
        'value_en',
    ];

    /**
     * Feature type constants
     */
    const TYPE_TEACHING_METHOD = 'teaching_method';
    const TYPE_LANGUAGE = 'language';
    const TYPE_CURRICULUM = 'curriculum';
    const TYPE_FACILITY = 'facility';
    const TYPE_EXTRACURRICULAR = 'extracurricular';
    const TYPE_AWARD = 'award';
    const TYPE_STRENGTH = 'strength';
    const TYPE_OTHER = 'other';

    /**
     * Get the kindergarten.
     */
    public function kindergarten(): BelongsTo
    {
        return $this->belongsTo(Kindergarten::class);
    }

    /**
     * Get localized value based on current locale.
     */
    public function getLocalizedValueAttribute(): string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'zh-TW' => $this->value_zh_tw,
            'zh-CN' => $this->value_zh_cn,
            default => $this->value_en,
        };
    }

    /**
     * Get localized feature type name.
     */
    public function getLocalizedTypeNameAttribute(): string
    {
        $locale = app()->getLocale();
        
        $types = [
            'teaching_method' => [
                'zh-TW' => '教學方法',
                'zh-CN' => '教学方法',
                'en' => 'Teaching Method',
            ],
            'language' => [
                'zh-TW' => '教學語言',
                'zh-CN' => '教学语言',
                'en' => 'Teaching Language',
            ],
            'curriculum' => [
                'zh-TW' => '課程特色',
                'zh-CN' => '课程特色',
                'en' => 'Curriculum',
            ],
            'facility' => [
                'zh-TW' => '設施',
                'zh-CN' => '设施',
                'en' => 'Facility',
            ],
            'extracurricular' => [
                'zh-TW' => '課外活動',
                'zh-CN' => '课外活动',
                'en' => 'Extracurricular',
            ],
            'award' => [
                'zh-TW' => '獎項',
                'zh-CN' => '奖项',
                'en' => 'Award',
            ],
            'strength' => [
                'zh-TW' => '學校優勢',
                'zh-CN' => '学校优势',
                'en' => 'Strength',
            ],
            'other' => [
                'zh-TW' => '其他',
                'zh-CN' => '其他',
                'en' => 'Other',
            ],
        ];

        return $types[$this->feature_type][$locale] ?? $types[$this->feature_type]['en'];
    }
}
