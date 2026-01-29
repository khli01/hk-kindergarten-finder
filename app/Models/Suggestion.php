<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Suggestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'kindergarten_id',
        'content',
        'category',
        'status',
        'admin_notes',
    ];

    /**
     * Category constants
     */
    const CATEGORY_SCHOOL_INFO = 'school_info';
    const CATEGORY_RANKING_FEEDBACK = 'ranking_feedback';
    const CATEGORY_FEATURE_REQUEST = 'feature_request';
    const CATEGORY_DATA_CORRECTION = 'data_correction';
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_OTHER = 'other';

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_PROCESSED = 'processed';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Get the user who submitted the suggestion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related kindergarten (if any).
     */
    public function kindergarten(): BelongsTo
    {
        return $this->belongsTo(Kindergarten::class);
    }

    /**
     * Get localized category name.
     */
    public function getLocalizedCategoryAttribute(): string
    {
        $locale = app()->getLocale();
        
        $categories = [
            'school_info' => [
                'zh-TW' => '學校資訊',
                'zh-CN' => '学校资讯',
                'en' => 'School Information',
            ],
            'ranking_feedback' => [
                'zh-TW' => '排名反饋',
                'zh-CN' => '排名反馈',
                'en' => 'Ranking Feedback',
            ],
            'feature_request' => [
                'zh-TW' => '功能建議',
                'zh-CN' => '功能建议',
                'en' => 'Feature Request',
            ],
            'data_correction' => [
                'zh-TW' => '資料更正',
                'zh-CN' => '资料更正',
                'en' => 'Data Correction',
            ],
            'general' => [
                'zh-TW' => '一般建議',
                'zh-CN' => '一般建议',
                'en' => 'General Suggestion',
            ],
            'other' => [
                'zh-TW' => '其他',
                'zh-CN' => '其他',
                'en' => 'Other',
            ],
        ];

        return $categories[$this->category][$locale] ?? $categories[$this->category]['en'];
    }

    /**
     * Get localized status name.
     */
    public function getLocalizedStatusAttribute(): string
    {
        $locale = app()->getLocale();
        
        $statuses = [
            'pending' => [
                'zh-TW' => '待處理',
                'zh-CN' => '待处理',
                'en' => 'Pending',
            ],
            'reviewed' => [
                'zh-TW' => '已審閱',
                'zh-CN' => '已审阅',
                'en' => 'Reviewed',
            ],
            'processed' => [
                'zh-TW' => '已處理',
                'zh-CN' => '已处理',
                'en' => 'Processed',
            ],
            'archived' => [
                'zh-TW' => '已存檔',
                'zh-CN' => '已存档',
                'en' => 'Archived',
            ],
        ];

        return $statuses[$this->status][$locale] ?? $statuses[$this->status]['en'];
    }

    /**
     * Scope for pending suggestions.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for filtering by category.
     */
    public function scopeOfCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeOfStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
