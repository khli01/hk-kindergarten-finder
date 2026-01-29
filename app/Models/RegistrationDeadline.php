<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class RegistrationDeadline extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kindergarten_id',
        'academic_year',
        'event_type',
        'deadline_date',
        'deadline_time',
        'notes_zh_tw',
        'notes_zh_cn',
        'notes_en',
        'source_url',
        'is_scraped',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline_date' => 'date',
        'deadline_time' => 'datetime:H:i',
        'is_scraped' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Event type constants
     */
    const TYPE_APPLICATION_START = 'application_start';
    const TYPE_APPLICATION_DEADLINE = 'application_deadline';
    const TYPE_INTERVIEW = 'interview';
    const TYPE_RESULT_ANNOUNCEMENT = 'result_announcement';
    const TYPE_REGISTRATION = 'registration';
    const TYPE_OPEN_DAY = 'open_day';
    const TYPE_BRIEFING_SESSION = 'briefing_session';
    const TYPE_OTHER = 'other';

    /**
     * Get the kindergarten.
     */
    public function kindergarten(): BelongsTo
    {
        return $this->belongsTo(Kindergarten::class);
    }

    /**
     * Get localized notes based on current locale.
     */
    public function getLocalizedNotesAttribute(): ?string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'zh-TW' => $this->notes_zh_tw,
            'zh-CN' => $this->notes_zh_cn,
            default => $this->notes_en,
        };
    }

    /**
     * Get localized event type name.
     */
    public function getLocalizedEventTypeAttribute(): string
    {
        $locale = app()->getLocale();
        
        $types = [
            'application_start' => [
                'zh-TW' => '申請開始',
                'zh-CN' => '申请开始',
                'en' => 'Application Start',
            ],
            'application_deadline' => [
                'zh-TW' => '申請截止',
                'zh-CN' => '申请截止',
                'en' => 'Application Deadline',
            ],
            'interview' => [
                'zh-TW' => '面試',
                'zh-CN' => '面试',
                'en' => 'Interview',
            ],
            'result_announcement' => [
                'zh-TW' => '結果公佈',
                'zh-CN' => '结果公布',
                'en' => 'Result Announcement',
            ],
            'registration' => [
                'zh-TW' => '註冊',
                'zh-CN' => '注册',
                'en' => 'Registration',
            ],
            'open_day' => [
                'zh-TW' => '開放日',
                'zh-CN' => '开放日',
                'en' => 'Open Day',
            ],
            'briefing_session' => [
                'zh-TW' => '簡介會',
                'zh-CN' => '简介会',
                'en' => 'Briefing Session',
            ],
            'other' => [
                'zh-TW' => '其他',
                'zh-CN' => '其他',
                'en' => 'Other',
            ],
        ];

        return $types[$this->event_type][$locale] ?? $types[$this->event_type]['en'];
    }

    /**
     * Check if deadline is upcoming (within next 30 days).
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->deadline_date->isBetween(now(), now()->addDays(30));
    }

    /**
     * Check if deadline has passed.
     */
    public function getIsPassedAttribute(): bool
    {
        return $this->deadline_date->isPast();
    }

    /**
     * Scope for upcoming deadlines.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('deadline_date', '>=', now())
                     ->orderBy('deadline_date');
    }

    /**
     * Scope for filtering by academic year.
     */
    public function scopeForYear(Builder $query, string $year): Builder
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope for verified deadlines.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }
}
